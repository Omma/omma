<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>, Adrian Woeltche
 *
 * @ORM\MappedSuperclass
 */
abstract class User extends BaseUser
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
