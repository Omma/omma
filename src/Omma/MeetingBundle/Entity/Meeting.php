<?php

namespace Omma\MeetingBundle\Entity;

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
     * @var MeetingRecurring
     *
     * @ORM\OneToMany(targetEntity="MeetingRecurring", mappedBy="meetingId", orphanRemoval=true)
     */
    protected $meetingRecurrings;

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
     * Set prev
     *
     * @param \Omma\MeetingBundle\Entity\Meeting $prev
     * @return Meeting
     */
    public function setPrev(\Omma\MeetingBundle\Entity\Meeting $prev = null)
    {
        $this->prev = $prev;

        return $this;
    }

    /**
     * Get prev
     *
     * @return \Omma\MeetingBundle\Entity\Meeting
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Set next
     *
     * @param \Omma\MeetingBundle\Entity\Meeting $next
     * @return Meeting
     */
    public function setNext(\Omma\MeetingBundle\Entity\Meeting $next = null)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Get next
     *
     * @return \Omma\MeetingBundle\Entity\Meeting
     */
    public function getNext()
    {
        return $this->next;
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
     * Set meetingRecurring
     *
     * @param \Omma\MeetingBundle\Entity\MeetingRecurring $meetingRecurring
     * @return Meeting
     */
    public function setMeetingRecurring(\Omma\MeetingBundle\Entity\MeetingRecurring $meetingRecurring = null)
    {
        $this->meetingRecurring = $meetingRecurring;

        return $this;
    }

    /**
     * Get meetingRecurring
     *
     * @return \Omma\MeetingBundle\Entity\MeetingRecurring
     */
    public function getMeetingRecurring()
    {
        return $this->meetingRecurring;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->meetingRecurring = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add meetingRecurring
     *
     * @param \Omma\MeetingBundle\Entity\MeetingRecurring $meetingRecurring
     * @return Meeting
     */
    public function addMeetingRecurring(\Omma\MeetingBundle\Entity\MeetingRecurring $meetingRecurring)
    {
        $this->meetingRecurring[] = $meetingRecurring;

        return $this;
    }

    /**
     * Remove meetingRecurring
     *
     * @param \Omma\MeetingBundle\Entity\MeetingRecurring $meetingRecurring
     */
    public function removeMeetingRecurring(\Omma\MeetingBundle\Entity\MeetingRecurring $meetingRecurring)
    {
        $this->meetingRecurring->removeElement($meetingRecurring);
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
}
