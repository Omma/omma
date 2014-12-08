<?php
namespace Omma\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MeetingRecurring
 *
 * @ORM\Table("omma_meeting_recurring")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class MeetingRecurring extends Base
{

    const TYPE_DAY = 1;

    const TYPE_WEEK = 2;

    const TYPE_MONTH = 3;

    const TYPE_YEAR = 4;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="meetingRecurrings")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\OneToMany(targetEntity="MeetingRecurringException", mappedBy="meetingRecurring")
     *
     * @var ArrayCollection
     */
    private $meetingRecurringExceptions;

    /**
     * @ORM\Column(name="date_start", type="datetime")
     *
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @ORM\Column(name="date_end", type="datetime")
     *
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @ORM\Column(name="type", type="integer")
     *
     * @var integer
     */
    private $type;

    /**
     * @ORM\Column(name="recurring", type="integer")
     *
     * @var integer
     */
    private $recurring;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->meetingRecurringExceptions = new ArrayCollection();
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
     *
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
     *
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
     *
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
     * Set meeting
     *
     * @param Meeting $meeting
     *
     * @return MeetingRecurring
     */
    public function setMeeting(Meeting $meeting)
    {
        if ($this->meeting !== $meeting) {
            $this->meeting = $meeting;
            if (null !== $meeting) {
                $meeting->addMeetingRecurring($this);
            }
        }

        return $this;
    }

    /**
     * Get meeting
     *
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Add meetingRecurringExceptions
     *
     * @param MeetingRecurringException $meetingRecurringException
     *
     * @return MeetingRecurring
     */
    public function addMeetingRecurringException(MeetingRecurringException $meetingRecurringException)
    {
        if ($this->meetingRecurringExceptions->contains($meetingRecurringException)) {
            $this->meetingRecurringExceptions->add($meetingRecurringException);
            $meetingRecurringException->setMeetingRecurring($this);
        }

        return $this;
    }

    /**
     * Remove meetingRecurringExceptions
     *
     * @param MeetingRecurringException $meetingRecurringException
     *
     * @return $this
     */
    public function removeMeetingRecurringException(MeetingRecurringException $meetingRecurringException)
    {
        if ($this->meetingRecurringExceptions->removeElement($meetingRecurringException)) {
            $meetingRecurringException->setMeetingRecurring(null);
        }

        return $this;
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

    /**
     * Set recurring
     *
     * @param integer $recurring
     *
     * @return MeetingRecurring
     */
    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;

        return $this;
    }

    /**
     * Get recurring
     *
     * @return integer
     */
    public function getRecurring()
    {
        return $this->recurring;
    }
}
