<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeetingRecurringException
 *
 * @ORM\Table("omma_meeting_recurring_exception")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringException extends Base
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="MeetingRecurring", inversedBy="meetingRecurringExceptions")
     * @ORM\JoinColumn(name="meeting_recurring_id", referencedColumnName="id", nullable=false)
     *
     * @var MeetingRecurring
     */
    private $meetingRecurring;

    /**
     * @ORM\Column(name="date", type="datetime")
     *
     * @var \DateTime
     */
    private $date;

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
     *
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
     * Set meetingRecurring
     *
     * @param MeetingRecurring $meetingRecurring
     *
     * @return MeetingRecurringException
     */
    public function setMeetingRecurring(MeetingRecurring $meetingRecurring = null)
    {
        if ($this->meetingRecurring !== $meetingRecurring) {
            $this->meetingRecurring = $meetingRecurring;
            if (null !== $meetingRecurring) {
                $meetingRecurring->addMeetingRecurringException($this);
            }
        }

        return $this;
    }

    /**
     * Get meetingRecurring
     *
     * @return MeetingRecurring
     */
    public function getMeetingRecurring()
    {
        return $this->meetingRecurring;
    }
}
