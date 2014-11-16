<?php

namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeetingRecurringException
 *
 * @ORM\Table("omma_meeting_recurring_exception")
 * @ORM\Entity
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringException
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
     * @ORM\ManyToOne(targetEntity="MeetingRecurring", inversedBy="meetingRecurringException")
     * @ORM\JoinColumn(name="meeting_recurring_id", referencedColumnName="id", nullable=false)
     */
    protected $meetingRecurringId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

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
     * Set date
     *
     * @param \DateTime $date
     * @return MeetingRecurringException
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set meetingRecurringId
     *
     * @param \Omma\AppBundle\Entity\MeetingRecurring $meetingRecurringId
     * @return MeetingRecurringException
     */
    public function setMeetingRecurringId(\Omma\AppBundle\Entity\MeetingRecurring $meetingRecurringId)
    {
        $this->meetingRecurringId = $meetingRecurringId;

        return $this;
    }

    /**
     * Get meetingRecurringId
     *
     * @return \Omma\AppBundle\Entity\MeetingRecurring
     */
    public function getMeetingRecurringId()
    {
        return $this->meetingRecurringId;
    }
}
