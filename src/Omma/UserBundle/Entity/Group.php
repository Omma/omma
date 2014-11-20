<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseGroup;

/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>, Adrian Woeltche
 *
 * @ORM\MappedSuperclass
 */
abstract class Group extends BaseGroup
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
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
