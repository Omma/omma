<?php
namespace Omma\UserBundle\Security;

use Application\Sonata\UserBundle\Entity\User;
use Omma\UserBundle\Ldap\LdapDirectory;
use Omma\UserBundle\Ldap\LdapDirectoryInterface;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapAuthtenticationProvider extends DaoAuthenticationProvider
{
    /**
     * @var LdapDirectory
     */
    private $ldapDirectory;

    public function __construct(
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        $providerKey,
        EncoderFactoryInterface $encoderFactory,
        LdapDirectoryInterface $ldapDirectory,
        $hideUserNotFoundExceptions = true
    ) {
        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);

        $this->ldapDirectory = $ldapDirectory;
    }

    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        if (!$user instanceof User and strlen($user->getLdapId) > 0) {
            throw new BadCredentialsException("No LDAP User");
        }
        $this->ldapDirectory->authenticate($user, $token);
    }
}
