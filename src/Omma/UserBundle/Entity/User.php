<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 * @ORM\MappedSuperclass
 */
abstract class User extends BaseUser
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ldapId;

}
