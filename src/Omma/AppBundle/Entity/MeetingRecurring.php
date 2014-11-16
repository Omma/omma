<?php

namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeetingRecurring
 *
 * @ORM\Table("omma_meeting_recurring")
 * @ORM\Entity
 *
 * @author Adrian Woeltche
 */
class MeetingRecurring
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
     * @var Meeting
     *
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="meetingRecurring")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     */
    protected $meetingId;

    /**
     * @var MeetingRecurringException
     *
     * @ORM\OneToMany(targetEntity="MeetingRecurringException", mappedBy="meetingRecurringId")
     */
    protected $meetingRecurringExceptions;

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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->meetingRecurringExceptions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return MeetingRecurring
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
     * @return MeetingRecurring
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
     * Set type
     *
     * @param string $type
     * @return MeetingRecurring
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set meetingId
     *
     * @param \Omma\AppBundle\Entity\Meeting $meetingId
     * @return MeetingRecurring
     */
    public function setMeetingId(\Omma\AppBundle\Entity\Meeting $meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * Get meetingId
     *
     * @return \Omma\AppBundle\Entity\Meeting
     */
    public function getMeetingId()
    {
        return $this->meetingId;
    }

    /**
     * Add meetingRecurringExceptions
     *
     * @param \Omma\AppBundle\Entity\MeetingRecurringException $meetingRecurringExceptions
     * @return MeetingRecurring
     */
    public function addMeetingRecurringException(\Omma\AppBundle\Entity\MeetingRecurringException $meetingRecurringExceptions)
    {
        $this->meetingRecurringExceptions[] = $meetingRecurringExceptions;

        return $this;
    }

    /**
     * Remove meetingRecurringExceptions
     *
     * @param \Omma\AppBundle\Entity\MeetingRecurringException $meetingRecurringExceptions
     */
    public function removeMeetingRecurringException(\Omma\AppBundle\Entity\MeetingRecurringException $meetingRecurringExceptions)
    {
        $this->meetingRecurringExceptions->removeElement($meetingRecurringExceptions);
    }

    /**
     * Get meetingRecurringExceptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeetingRecurringExceptions()
    {
        return $this->meetingRecurringExceptions;
    }
}
