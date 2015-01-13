<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseGroup;

/**
 * @ORM\MappedSuperclass
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>, Adrian Woeltche
 */
abstract class Group extends BaseGroup
{

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * cn from ldap. used for syncing.
     * @var string
     */
    protected $ldapId;

    /**
     * Set ldapId
     *
     * @param string $ldapId
     * @return Group
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
