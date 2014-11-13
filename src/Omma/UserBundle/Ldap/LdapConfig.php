<?php
namespace Omma\UserBundle\Ldap;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapConfig
{
    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var string
     */
    protected $security;

    /**
     * @var string
     */
    protected $bindName;

    /**
     * @var string
     */
    protected $bindPassword;

    /**
     * @var string
     */
    protected $baseDn;

    /**
     * @var array
     */
    protected $userConfig;

    /**
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
            "bindName"     => null,
            "bindPassword" => null,
            "baseDn"       => null,
            "users"        => array(),
            "groups"       => array(),
        );
        $this->enabled = $config['enabled'];
        $this->hostname = $config['hostname'];
        $this->port = $config['port'];
        $this->security = $config['security'];
        $this->bindName = $config['bindName'];
        $this->bindPassword = $config['bindPassword'];

        $baseDn = $config['baseDn'];

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
