<?php
namespace Omma\UserBundle\Ldap;

use Omma\UserBundle\Entity\User;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sonata\UserBundle\Entity\UserManager;

/**
 *
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

    public function __construct(LdapDirectory $directory, UserManager $userManager, LoggerInterface $logger = null)
    {
        $this->directory = $directory;
        $this->userManager = $userManager;
        $this->logger = $logger;
    }

    public function sync()
    {
        if (!$this->directory->isEnabled()) {
            if (null !== $this->logger) {
                $this->logger->error("Ldap not configured. Nothing to sync.");
                return;
            }
        }
        $users = $this->directory->getUsers();
        $groups = $this->directory->getGroups();
        foreach ($users as $dn => $data) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("Syncing user %s", $dn));
            }
            $this->doSyncUser($data);
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
     */
    private function doSyncUser($data)
    {
        $user = $this->findOrCreateUser($data);
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
    private function findOrCreateUser($data)
    {
        // find already synced user
        $user = $this->userManager->findUserBy(array("ldapId" => $data['dn']));
        if (null !== $user) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf("updating db user %s", $user));
            }
            return $user;
        }

        // find user with same login

        /** @var User $user */
        $user = $this->userManager->findUserByUsername($data['username']);
        if (null !== $user) {

            // dont sync user with different ldap id
            if (null !== $user->getLdapId()) {
                if (null !== $this->logger) {
                    $this->logger->warning(sprintf("found conflict with existing user %s", $user->getUsername()));
                }
                return null;
            }

            if (null !== $this->logger) {
                $this->logger->info(sprintf("updating db user %s", $user));
            }

            return $user;
        }
        if (null !== $this->logger) {
            $this->logger->info(sprintf("creating new user %s", $data['username']));
        }

        return $this->userManager->createUser();
    }
}
