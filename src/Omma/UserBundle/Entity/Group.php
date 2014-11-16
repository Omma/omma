<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseGroup;

/**
 * @author Florian Pfitzer <pfitzer@w3p.cc>, Adrian Woeltche
 *
 * @ORM\MappedSuperclass
 */
class Group extends BaseGroup
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ldapId;

    /**
     * @var \Omma\AppBundle\Entity\Meeting
     *
     * @ORM\ManyToMany(targetEntity="\Omma\AppBundle\Entity\Meeting", mappedBy="groups")
     */
    protected $meetings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->meetings = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    /**
     * Add meetings
     *
     * @param \Omma\AppBundle\Entity\Meeting $meetings
     * @return Group
     */
    public function addMeeting(\Omma\AppBundle\Entity\Meeting $meetings)
    {
        $this->meetings[] = $meetings;

        return $this;
    }

    /**
     * Remove meetings
     *
     * @param \Omma\AppBundle\Entity\Meeting $meetings
     */
    public function removeMeeting(\Omma\AppBundle\Entity\Meeting $meetings)
    {
        $this->meetings->removeElement($meetings);
    }

    /**
     * Get meetings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }
}
