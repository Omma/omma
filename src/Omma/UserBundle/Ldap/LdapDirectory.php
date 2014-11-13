<?php
namespace Omma\UserBundle\Ldap;

use Application\Sonata\UserBundle\Entity\User;
use Toyota\Component\Ldap\Core\Manager;
use Toyota\Component\Ldap\Core\Node;
use Toyota\Component\Ldap\Core\SearchResult;
use Toyota\Component\Ldap\Platform\Native\Driver;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapDirectory
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

    public function isEnabled()
    {
        return $this->config->isEnabled();
    }

    public function getUsers()
    {
        if (!$this->config->isEnabled()) {
            return array();
        }

        $userConfig = $this->config->getUserConfig();
        $filter = $userConfig['filter'];
        $result = $this->getManager()->search($userConfig['dn'], $filter, true,
            array_values($userConfig['mapping']));
        $users = $this->mapResult($result, $userConfig['mapping']);

        return $users;
    }

    public function getGroups()
    {
        if (!$this->config->isEnabled()) {
            return array();
        }

        $groupConfig = $this->config->getGroupConfig();
        $filter = $groupConfig['filter'];
        $result = $this->getManager()->search($groupConfig['dn'], $filter, true,
            array_values($groupConfig['mapping']));

        return $this->mapResult($result, $groupConfig['mapping'], array("members"));
    }

    /**
     * @return Manager
     */
    protected function getManager()
    {
        if (null !== $this->manager) {
            return $this->manager;
        }
        $params = array(
            "hostname" => $this->config->getHostname(),
            "base_dn"  => $this->config->getBaseDn(),
            "options"  => array(
                LDAP_OPT_NETWORK_TIMEOUT => 5,
                LDAP_OPT_PROTOCOL_VERSION => 3,
                LDAP_OPT_REFERRALS => 0, // avoid problems with ActiveDircectory
            )
        );
        if (null !== $this->config->getPort()) {
            $params['port'] = $this->config->getPort();
        }
        if (null !== $this->config->getSecurity() && "None" != $this->config->getSecurity()) {
            $params['security'] = $this->config->getSecurity();
        }
        $manager = new Manager($params, new Driver());
        $manager->connect();
        if (null !== $this->config->getBindName()) {
            $manager->bind($this->config->getBindName(), $this->config->getBindPassword());
        } else {
            $manager->bind();
        }

        return $this->manager = $manager;
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
