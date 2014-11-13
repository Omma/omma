<?php
namespace Omma\UserBundle\Ldap;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Omma\UserBundle\Entity\GroupEntityManager;
use Omma\UserBundle\Entity\UserEntityManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Sync users from LDAP directory with database
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
        LdapDirectory $directory,
        UserEntityManager $userManager,
        GroupEntityManager $groupManager,
        LoggerInterface $logger = null)
    {
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
        // @TODO: Delete old users

        $groupList = array();
        $groups = $this->directory->getGroups();
        foreach ($groups as $dn => $data) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("Syncing group %s", $dn));
            }
            $groupList[] = $this->syncGroup($data, $userList);
        }

        // Delete old groups
        $groups = $this->groupManager->createQueryBuilder("g")
            ->select("g")
            ->where("g.ldapId != '' AND g.ldapId NOT IN (:groups)")
            ->setParameter("groups", $groupList)
            ->getQuery()
            ->getResult()
        ;
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
            // don't sync user with different ldap id
            if (strlen($user->getLdapId()) > 0) {
                if (null !== $this->logger) {
                    $this->logger->warning(sprintf("found conflict with existing user %s (id: %d). Skipping",
                        $user->getUsername(), $user->getId()));
                }

                return null;
            }

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
                ->setParameter("members", $memberList)
            ;
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
            // don't sync group with different ldap id
            if (strlen($group->getLdapId()) > 0) {
                if (null !== $this->logger) {
                    $this->logger->warning(sprintf("found conflict with existing group %s (id: %d). Skipping",
                        $group->getName(), $group->getId()));
                }

                return null;
            }

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
