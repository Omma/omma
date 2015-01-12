<?php
namespace Omma\UserBundle\Ldap;

/**
 * Ldap configuration.
 * Used by {@link LdapDirectory}
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapConfig
{
    /**
     * ldap is enabled
     * @var boolean
     */
    protected $enabled;

    /**
     * ldap server hostname
     * @var string
     */
    protected $hostname;

    /**
     * ldap server port
     * @var integer
     */
    protected $port;

    /**
     * ldap server security, can be on of
     * - None: no security
     * - SSL: SSL encryption
     * - TLS: TLS encryption
     * @var string
     */
    protected $security;

    /**
     * ldap bind name
     * @var string
     */
    protected $bindName;

    /**
     * ldap bind password
     * @var string
     */
    protected $bindPassword;

    /**
     * base dn
     * @var string
     */
    protected $baseDn;

    /**
     * configuration for user sync settings
     *
     * @see Omma\UserBundle\DependencyInjection\Configuration::ldapConfig
     * @see php app/console config:dump-reference OmmaUserBundle
     * @var array
     */
    protected $userConfig;

    /**
     * configuration for group sync settings
     * @see Omma\UserBundle\DependencyInjection\Configuration::ldapConfig
     * @see php app/console config:dump-reference OmmaUserBundle
     * @var array
     */
    protected $groupConfig;

    public function __construct(array $config)
    {
        $config += array(
            "enabled"      => false,
            "hostname"     => null,
            "port"         => null,
            "security"     => null,
            "bind_name"     => null,
            "bind_password" => null,
            "base_dn"       => null,
            "users"        => array(),
            "groups"       => array(),
        );
        $this->enabled = $config['enabled'];
        $this->hostname = $config['hostname'];
        $this->port = $config['port'];
        $this->security = $config['security'];
        $this->bindName = $config['bind_name'];
        $this->bindPassword = $config['bind_password'];

        $baseDn = $config['base_dn'];

        // convert dns name to into LDAP dn
        if (strlen($baseDn) > 0 && stripos($baseDn, "=") === false) {
            $split = explode(".", $baseDn);
            $baseDn = "dc=" . join(",dc=", $split);
        }
        $this->baseDn = $baseDn;
        $this->userConfig = $config['users'];
        $this->groupConfig = $config['groups'];
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string|null
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return integer|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string|null
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * @return string|null
     */
    public function getBindName()
    {
        return $this->bindName;
    }

    /**
     * @return string|null
     */
    public function getBindPassword()
    {
        return $this->bindPassword;
    }

    /**
     * @return string
     */
    public function getBaseDn()
    {
        return $this->baseDn;
    }

    /**
     * @return array
     */
    public function getUserConfig()
    {
        return $this->userConfig;
    }

    /**
     * @return array
     */
    public function getGroupConfig()
    {
        return $this->groupConfig;
    }
}
