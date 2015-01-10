<?php
namespace Omma\UserBundle\Ldap;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Omma\UserBundle\Entity\GroupEntityManager;
use Omma\UserBundle\Entity\UserEntityManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Sync users from LDAP directory with database.
 * Users and Groups which are not present in the database will be added,
 * Users and Groups which are present in ldap an database will be updated,
 * Users and Groups which were synced previously will be deleted or marked as deleted.
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapSyncService implements LoggerAwareInterface
{
    /**
     * @var LdapDirectory
     */
    protected $directory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var UserEntityManager
     */
    private $userManager;

    /**
     * @var GroupEntityManager
     */
    private $groupManager;

    public function __construct(
        LdapDirectoryInterface $directory,
        UserEntityManager $userManager,
        GroupEntityManager $groupManager,
        LoggerInterface $logger = null
    ) {
        $this->directory = $directory;
        $this->userManager = $userManager;
        $this->logger = $logger;
        $this->groupManager = $groupManager;
    }

    public function sync()
    {
        if (!$this->directory->isEnabled()) {
            if (null !== $this->logger) {
                $this->logger->error("Ldap not configured. Nothing to sync.");

                return;
            }
        }
        $userList = array();
        $users = $this->directory->getUsers();
        foreach ($users as $dn => $data) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("Syncing user %s", $dn));
            }
            $user = $this->syncUser($data);
            $userList[$user->getUsername()] = $user;
        }

        // Delete old users
        $query = $this->userManager->createQueryBuilder("u")
            ->select("u")
            ->where("u.ldapId != '' and u.enabled = 1")
        ;
        if (!empty($userList)) {
            $query
                ->andWhere("u NOT IN (:users)")
                ->setParameter("users", array_values($userList)) // can't be an associative array
            ;
        }
        /** @var User[] $users */
        $users = $query
            ->getQuery()
            ->getResult()
        ;
        foreach ($users as $user) {
            if (null !== $this->logger) {
                $this->logger->warning(sprintf("disabling user '%s'", $user));
            }
            $user->setEnabled(false);
            $this->userManager->updateUser($user);
        }

        // sync groups
        $groupList = array();
        $groups = $this->directory->getGroups();
        foreach ($groups as $dn => $data) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("Syncing group %s", $dn));
            }
            $groupList[] = $this->syncGroup($data, $userList);
        }

        // Delete old groups
        $query = $this->groupManager->createQueryBuilder("g")
            ->select("g")
            ->where("g.ldapId != ''")
        ;
        if (!empty($groupList)) {
            $query
                ->andWhere("g NOT IN (:groups)")
                ->setParameter("groups", array_values($groupList))
            ;
        }
        $groups = $query
            ->getQuery()
            ->getResult()
        ;

        foreach ($groups as $group) {
            if (null !== $this->logger) {
                $this->logger->warning(sprintf("deleting group '%s'", $group));
            }
            $this->groupManager->deleteGroup($group);
        }
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param array $data ldap data
     *
     * @return User|null
     */
    protected function syncUser($data)
    {
        $user = $this->findOrCreateUser($data);
        if (null === $user) {
            return null;
        }
        $user->setLdapId($data['dn']);
        $user->setUsername($data['username']);
        // email cannot be null
        if (null === $data['email']) {
            $data['email'] = $data['username'];
        }
        $user->setEmail($data['email']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setPlainPassword("ldapuser");
        $user->setEnabled(true);

        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * @param array $data ldap data
     *
     * @return User
     */
    protected function findOrCreateUser($data)
    {
        // find already synced user
        $user = $this->userManager->findUserBy(array("ldapId" => $data['dn']));
        if (null !== $user) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("updating db user %s (id: %d)", $user, $user->getId()));
            }

            return $user;
        }

        // find user with same login

        /** @var User $user */
        $user = $this->userManager->findUserByUsername($data['username']);
        if (null !== $user) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("updating db user %s (id: %d)", $user, $user->getId()));
            }

            return $user;
        }
        if (null !== $this->logger) {
            $this->logger->info(sprintf("creating new user %s", $data['username']));
        }

        return $this->userManager->createUser();
    }

    /**
     * @param array  $data  ldap data
     * @param User[] $users
     *
     * @return Group|null
     */
    protected function syncGroup(array $data, array $users)
    {
        $group = $this->findOrCreateGroup($data);
        if (null === $group) {
            return null;
        }
        $group
            ->setLdapId($data['dn'])
            ->setName($data['name']);

        $this->groupManager->updateGroup($group);

        $members = (array) $data['members'];
        $memberList = array();

        foreach ($members as $member) {
            if (!isset($users[$member])) {
                continue;
            }
            $user = $users[$member];
            $memberList[] = $user;
            if (null !== $this->logger) {
                $this->logger->info(sprintf("adding user '%s' to group '%s'", $user, $group));
            }
            $user->addGroup($group);
            $this->userManager->updateUser($user);
        }

        // remove other ldap members from group
        $query = $this->userManager->createQueryBuilder("u")
            ->select("u")
            ->where(":group MEMBER OF u.groups AND u.ldapId != ''")
            ->setParameter("group", $group)
        ;
        if (!empty($members)) {
            $query
                ->andWhere("u NOT IN (:members)")
                ->setParameter("members", $memberList);
        }
        /** @var User[] $result */
        $result = $query
            ->getQuery()
            ->getResult()
        ;

        foreach ($result as $user) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("removing user '%s' from group '%s'", $user, $group));
            }
            $user->removeGroup($group);
            $this->userManager->updateUser($user);
        }

        return $group;
    }

    /**
     * @param array $data ldap data
     *
     * @return Group
     */
    protected function findOrCreateGroup(array $data)
    {
        // find already synced group
        $group = $this->groupManager->findGroupBy(array("ldapId" => $data['dn']));
        if (null !== $group) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("updating db group %s (id: %d)", $group, $group->getId()));
            }

            return $group;
        }

        // find group by name
        /** @var Group $group */
        $group = $this->groupManager->findGroupByName($data['name']);
        if (null !== $group) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("updating db group %s (id: %d)", $group, $group->getId()));
            }

            return $group;
        }

        if (null !== $this->logger) {
            $this->logger->info(sprintf("creating new group %s", $data['name']));
        }

        return $this->groupManager->createGroup($data['name']);
    }
}
