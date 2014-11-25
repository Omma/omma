<?php
namespace Omma\UserBundle\Tests\Ldap;

use Application\Sonata\UserBundle\Entity\User;
use Omma\UserBundle\Ldap\LdapDirectoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class TestLdapDirectory implements LdapDirectoryInterface
{
    /**
     * @var array
     */
    protected $users;

    /**
     * @var array
     */
    protected $groups;

    public function isEnabled()
    {
        return true;
    }

    public function authenticate(User $user, UsernamePasswordToken $token)
    {
    }

    public function getUsers()
    {
        return array(
            "uid=test1,ou=users,dc=omma,dc=local" => array(
                "dn" => "uid=test1,ou=users,dc=omma,dc=local",
                "username" => "test1",
                "email" => "test1@omma.local",
                "firstname" => "Test",
                "lastname" => "Tester"
            ),
            "uid=test2,ou=users,dc=omma,dc=local" => array(
                "dn" => "uid=test2,ou=users,dc=omma,dc=local",
                "username" => "test2",
                "email" => "test2@omma.local",
                "firstname" => "Test2",
                "lastname" => "Tester"
            ),
            "uid=test5,ou=users,dc=omma,dc=local" => array(
                "dn" => "uid=test5,ou=users,dc=omma,dc=local",
                "username" => "test5",
                "email" => "test5@omma.local",
                "firstname" => "test5",
                "lastname" => "Tester"
            ),
        );
    }

    public function getGroups()
    {
        return array(
            "cn=admin,ou=groups,dc=omma,dc=local" => array(
                "dn" => "cn=admin,ou=groups,dc=omma,dc=local",
                "name" => "admin",
                "members" => array(
                    "test1",
                    "test2",
                    "test3"
                )
            ),
            "cn=it,ou=groups,dc=omma,dc=local" => array(
                "dn" => "cn=it,ou=groups,dc=omma,dc=local",
                "name" => "it",
                "members" => array(
                    "test1",
                    "test2",
                    "test3",
                    "test4",
                )
            ),
        );
    }
}
