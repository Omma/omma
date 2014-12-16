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

    const TYPE_DAY = "day";

    const TYPE_WEEK = "week";

    const TYPE_MONTH = "month";

    const TYPE_YEAR = "year";

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Meeting", mappedBy="meetingRecurring")
     *
     * @var ArrayCollection
     */
    private $meetings;

    /**
     * @ORM\OneToMany(targetEntity="MeetingRecurringException", mappedBy="meetingRecurring")
     *
     * @var ArrayCollection
     */
    private $meetingRecurringExceptions;

    /**
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @ORM\Column(name="type", type="string")
     *
     * @var integer
     */
    private $type;

    /**
     * @ORM\Column(name="recurring", type="array")
     *
     * @var array
     */
    private $config = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->meetings = new ArrayCollection();
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
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function addMeeting(Meeting $meeting)
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings->add($meeting);
            $meeting->setMeetingRecurring($this);
        }

        return $this;
    }

    /**
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function removeMeeting(Meeting $meeting)
    {
        if ($this->meetings->removeElement($meeting)) {
            $meeting->setMeetingRecurring(null);
        }

        return $this;
    }

    /**
     * @return Meeting[]
     */
    public function getMeetings()
    {
        return $this->meetings;
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
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return self
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }
}
