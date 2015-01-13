<?php
namespace Omma\UserBundle\Ldap;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Toyota\Component\Ldap\Core\Manager;
use Toyota\Component\Ldap\Core\Node;
use Toyota\Component\Ldap\Core\SearchResult;
use Toyota\Component\Ldap\Exception\BindException;
use Toyota\Component\Ldap\Platform\Native\Driver;

class LdapDirectory implements LdapDirectoryInterface
{
    /**
     * @var LdapConfig
     */
    private $config;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @param LdapConfig $config
     */
    public function __construct(LdapConfig $config)
    {
        $this->config = $config;
    }

    /**
     * whether ldap is enabled
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->config->isEnabled();
    }

    /**
     * Authenticate a user against the ldap directory
     * throw an {@link Symfony\Component\Security\Core\Exception\BadCredentialsException} when failed
     *
     * @param User                  $user
     * @param UsernamePasswordToken $token
     */
    public function authenticate(User $user, UsernamePasswordToken $token)
    {
        if (!$this->config->isEnabled()) {
            throw new BadCredentialsException("Ldap not enabled");
        }
        try {
            $this->getManager($user->getLdapId(), $token->getCredentials());
        } catch (BindException $e) {
            throw new BadCredentialsException("Ldap login failed");
        }
    }

    /**
     * gets users from ldap
     * @return array
     */
    public function getUsers()
    {
        if (!$this->config->isEnabled()) {
            return array();
        }

        $userConfig = $this->config->getUserConfig();
        $filter = $userConfig['filter'];
        $result = $this->getManager()->search(
            $userConfig['dn'],
            $filter,
            true,
            array_values($userConfig['mapping'])
        );
        $users = $this->mapResult($result, $userConfig['mapping']);

        return $users;
    }

    /**
     * get groups by ldap
     * @return array
     */
    public function getGroups()
    {
        if (!$this->config->isEnabled()) {
            return array();
        }

        $groupConfig = $this->config->getGroupConfig();
        $filter = $groupConfig['filter'];
        $result = $this->getManager()->search(
            $groupConfig['dn'],
            $filter,
            true,
            array_values($groupConfig['mapping'])
        );

        return $this->mapResult($result, $groupConfig['mapping'], array("members"));
    }

    /**
     * @param null|string $username
     * @param null|string $password
     *
     * @return Manager
     */
    protected function getManager($username = null, $password = null)
    {
        // return cached manager for configured bind dn, when no username is provided
        if (null !== $this->manager and null === $username) {
            return $this->manager;
        }
        $params = array(
            "hostname" => $this->config->getHostname(),
            "base_dn"  => $this->config->getBaseDn(),
            "options"  => array(
                LDAP_OPT_NETWORK_TIMEOUT  => 5,
                LDAP_OPT_PROTOCOL_VERSION => 3,
                LDAP_OPT_REFERRALS        => 0, // avoid problems with ActiveDircectory
            ),
        );
        if (null !== $this->config->getPort()) {
            $params['port'] = $this->config->getPort();
        }
        if (null !== $this->config->getSecurity() && "None" != $this->config->getSecurity()) {
            $params['security'] = $this->config->getSecurity();
        }
        $manager = new Manager($params, new Driver());
        $manager->connect();

        if (null !== $username) {
            $manager->bind($username, $password);
        } elseif (null !== $this->config->getBindName()) {
            $manager->bind($this->config->getBindName(), $this->config->getBindPassword());
        } else {
            $manager->bind();
        }

        if (null === $username) {
            $this->manager = $manager;
        }

        return $manager;
    }

    /**
     * Map ldap result to a normal array
     *
     * @param SearchResult $result
     * @param array        $mapping
     * @param array        $asArray list of attributes to get as array instead of single value
     *
     * @return array
     */
    protected function mapResult(SearchResult $result, array $mapping, array $asArray = array())
    {
        $output = array();
        foreach ($result as $node) {
            /** @var $node Node */
            $data = array(
                "dn" => $node->getDn(),
            );
            foreach ($mapping as $localProperty => $ldapProperty) {
                if (!$node->has($ldapProperty)) {
                    $data[$localProperty] = null;
                    continue;
                }
                $property = $node->get($ldapProperty);
                if (in_array($localProperty, $asArray)) {
                    $data[$localProperty] = $property->getValues();
                } else {
                    $data[$localProperty] = $node->get($ldapProperty)->current();
                }
            }
            $output[$node->getDn()] = $data;
        }

        return $output;
    }
}
