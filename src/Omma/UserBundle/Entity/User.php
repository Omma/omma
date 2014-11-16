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
     * @var \Omma\AppBundle\Entity\Meeting
     *
     * @ORM\ManyToMany(targetEntity="\Omma\AppBundle\Entity\Meeting", mappedBy="users")
     */
    protected $meetings;

    /**
     * @var \Omma\AppBundle\Entity\Task
     *
     * @ORM\OneToMany(targetEntity="\Omma\AppBundle\Entity\Task", mappedBy="user")
     */
    protected $tasks;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->meetings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    /**
     * Add meetings
     *
     * @param \Omma\AppBundle\Entity\Meeting $meetings
     * @return User
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

    /**
     * Add tasks
     *
     * @param \Omma\AppBundle\Entity\Task $tasks
     * @return User
     */
    public function addTask(\Omma\AppBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Omma\AppBundle\Entity\Task $tasks
     */
    public function removeTask(\Omma\AppBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
