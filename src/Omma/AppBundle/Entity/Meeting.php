<?php

namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meeting
 *
 * @ORM\Table("omma_meeting")
 * @ORM\Entity
 *
 * @author Adrian Woeltche
 */
class Meeting
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="meetings")
     * @ORM\JoinTable(name="omma_meeting_users")
     */
    protected $users;

    /**
     * @var \Application\Sonata\UserBundle\Entity\Group
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\Group", inversedBy="meetings")
     * @ORM\JoinTable(name="omma_meeting_groups")
     */
    protected $groups;

    /**
     * @var MeetingRecurring
     *
     * @ORM\OneToMany(targetEntity="MeetingRecurring", mappedBy="meetingId", orphanRemoval=true)
     */
    protected $meetingRecurrings;

    /**
     * @var Task
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="meeting", orphanRemoval=true)
     */
    protected $tasks;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var Meeting
     *
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="next")
     * @ORM\JoinColumn(name="prev", referencedColumnName="id")
     */
    protected $prev;

    /**
     * @var Meeting
     *
     * @ORM\OneToOne(targetEntity="Meeting", mappedBy="prev")
     */
    protected $next;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime")
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime")
     */
    protected $dateEnd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->meetingRecurrings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Meeting
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return Meeting
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return Meeting
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Add users
     *
     * @param \Application\Sonata\UserBundle\Entity\User $users
     * @return Meeting
     */
    public function addUser(\Application\Sonata\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Application\Sonata\UserBundle\Entity\User $users
     */
    public function removeUser(\Application\Sonata\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add groups
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groups
     * @return Meeting
     */
    public function addGroup(\Application\Sonata\UserBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groups
     */
    public function removeGroup(\Application\Sonata\UserBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add meetingRecurrings
     *
     * @param \Omma\AppBundle\Entity\MeetingRecurring $meetingRecurrings
     * @return Meeting
     */
    public function addMeetingRecurring(\Omma\AppBundle\Entity\MeetingRecurring $meetingRecurrings)
    {
        $this->meetingRecurrings[] = $meetingRecurrings;

        return $this;
    }

    /**
     * Remove meetingRecurrings
     *
     * @param \Omma\AppBundle\Entity\MeetingRecurring $meetingRecurrings
     */
    public function removeMeetingRecurring(\Omma\AppBundle\Entity\MeetingRecurring $meetingRecurrings)
    {
        $this->meetingRecurrings->removeElement($meetingRecurrings);
    }

    /**
     * Get meetingRecurrings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeetingRecurrings()
    {
        return $this->meetingRecurrings;
    }

    /**
     * Set prev
     *
     * @param \Omma\AppBundle\Entity\Meeting $prev
     * @return Meeting
     */
    public function setPrev(\Omma\AppBundle\Entity\Meeting $prev = null)
    {
        $this->prev = $prev;

        return $this;
    }

    /**
     * Get prev
     *
     * @return \Omma\AppBundle\Entity\Meeting
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Set next
     *
     * @param \Omma\AppBundle\Entity\Meeting $next
     * @return Meeting
     */
    public function setNext(\Omma\AppBundle\Entity\Meeting $next = null)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Get next
     *
     * @return \Omma\AppBundle\Entity\Meeting
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Add tasks
     *
     * @param \Omma\AppBundle\Entity\Task $tasks
     * @return Meeting
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
