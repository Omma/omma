<?php
/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
namespace Omma\UserBundle\Ldap;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Handles communication with ldap directory
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
interface LdapDirectoryInterface
{
    /**
     * @return boolean ldap is enabled
     */
    public function isEnabled();

    /**
     * Authenticate a user against a ldap directory
     *
     * @param User                  $user
     * @param UsernamePasswordToken $token
     *
     * @throws \Symfony\Component\Security\Core\Exception\BadCredentialsException on failed authentication
     */
    public function authenticate(User $user, UsernamePasswordToken $token);

    /**
     * Get all ldap users
     *
     * @return array
     */
    public function getUsers();

    /**
     * Get all ldap groups
     *
     * @return array
     */
    public function getGroups();
}
