<?php
namespace Omma\UserBundle\Ldap;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sonata\UserBundle\Entity\GroupManager;
use Sonata\UserBundle\Entity\UserManager;

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
     * @var UserManager
     */
    private $userManager;

    /**
     * @var GroupManager
     */
    private $groupManager;

    public function __construct(
        LdapDirectory $directory,
        UserManager $userManager,
        GroupManager $groupManager,
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

        $groups = $this->directory->getGroups();
        foreach ($groups as $dn => $data) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("Syncing group %s", $dn));
            }
            $this->syncGroup($data, $userList);
        }
        // @TODO: Delete old groups
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
            if (null !== $user->getLdapId()) {
                if (null !== $this->logger) {
                    $this->logger->warning(sprintf("found conflict with existing user %s (id: %d)",
                            $user->getUsername(), $user->getId())
                    );
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

        foreach ($data['members'] as $member) {
            if (!isset($users[$member])) {
                continue;
            }
            $user = $users[$member];
            if (null !== $this->logger) {
                $this->logger->info(sprintf("adding user '%s' to group '%s'", $user, $group));
            }
            $user->addGroup($group);
            $this->userManager->updateUser($user);
            // @TODO: remove from groups
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
            if (null !== $group->getLdapId()) {
                if (null !== $this->logger) {
                    $this->logger->warning(sprintf("found conflict with existing user %s (id: %d)", $group->getName(),
                            $group->getId()
                    ));
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
