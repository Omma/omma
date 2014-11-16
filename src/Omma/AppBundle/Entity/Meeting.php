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
     * Constructor
     */
    public function __construct()
    {
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
}
