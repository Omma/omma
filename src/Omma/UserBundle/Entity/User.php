<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * @ORM\MappedSuperclass
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>, Adrian Woeltche
 */
abstract class User extends BaseUser
{

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * cn from ldap. Used for syncing
     * @var string
     */
    protected $ldapId;

    /**
     * Set ldapId
     *
     * @param string $ldapId
     * @return User
     */
    public function setLdapId($ldapId)
    {
        $this->ldapId = $ldapId;

        return $this;
    }

    /**
     * Get ldapId
     *
     * @return string
     */
    public function getLdapId()
    {
        return $this->ldapId;
    }
}
