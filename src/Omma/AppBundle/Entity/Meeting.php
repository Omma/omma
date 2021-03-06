<?php
namespace Omma\AppBundle\Entity;

use Application\Sonata\UserBundle\Entity\Group;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Meeting
 *
 * @ORM\Table("omma_meeting")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class Meeting extends Base
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Omma\AppBundle\Entity\Attendee", mappedBy="meeting", orphanRemoval=true, cascade="all")
     *
     * @var ArrayCollection
     */
    protected $attendees;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\Group", inversedBy="meetings", orphanRemoval=true)
     * @ORM\JoinTable(name="omma_meeting_groups")
     *
     * @var Group
     */
    protected $groups;

    /**
     * @ORM\ManyToOne(targetEntity="MeetingRecurring", inversedBy="meetings", cascade={"persist"})
     *
     * @var MeetingRecurring
     */
    protected $meetingRecurring;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="meeting", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    protected $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="meeting", orphanRemoval=true, cascade={"remove"})
     *
     * @var ArrayCollection
     */
    protected $agendas;

    /**
     * @ORM\OneToOne(targetEntity="Protocol", mappedBy="meeting", orphanRemoval=true, cascade={"remove"})
     *
     * @var Protocol
     */
    protected $protocol;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="meeting", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    protected $files;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @NotBlank()
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="next", cascade={"persist"})
     * @ORM\JoinColumn(name="prev", referencedColumnName="id")
     *
     * @var Meeting
     */
    protected $prev;

    /**
     * @ORM\OneToOne(targetEntity="Meeting", mappedBy="prev", cascade={"persist"})
     *
     * @var Meeting
     */
    protected $next;

    /**
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     * @NotBlank()
     * @DateTime()
     *
     * @var \DateTime
     */
    protected $dateStart;

    /**
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     * @NotBlank()
     * @DateTime()
     *
     * @var \DateTime
     */
    protected $dateEnd;

    /**
     * Temporary and not saved meeting
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @var boolean
     */
    protected $temp = false;

    /**
     * @var boolean
     */
    protected $sendInvitations = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attendees = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->agendas = new ArrayCollection();
        $this->files = new ArrayCollection();
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
     *
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
     * @param Attendee[] $attendees
     *
     * @return $this
     */
    public function setAttendees($attendees)
    {
        $this->attendees->clear();
        foreach ($attendees as $attendee) {
            $this->addAttendee($attendee);
        }

        return $this;
    }

    /**
     * @param Attendee $attendee
     *
     * @return $this
     */
    public function addAttendee(Attendee $attendee)
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees->add($attendee);
            $attendee->setMeeting($this);
        }

        return $this;
    }

    /**
     * @param Attendee $attendee
     *
     * @return $this
     */
    public function removeAttendee(Attendee $attendee)
    {
        if ($this->attendees->removeElement($attendee)) {
            $attendee->setMeeting(null);
        }

        return $this;
    }

    /**
     * @return Attendee[]
     */
    public function getAttendees()
    {
        return $this->attendees;
    }

    /**
     * Add group
     *
     * @param Group $group
     * @return Meeting
     */
    public function addGroup(Group $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addMeeting($this);
        }

        return $this;
    }

    /**
     * Remove group
     *
     * @param Group $group
     *
     * @return $this
     */
    public function removeGroup(Group $group)
    {
        if ($this->groups->removeElement($group)) {
            $this->groups->add($group);
            $group->removeMeeting($this);
        }

        return $this;
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
     * @param MeetingRecurring $meetingRecurring
     *
     * @return $this
     */
    public function setMeetingRecurring(MeetingRecurring $meetingRecurring = null)
    {
        if ($this->meetingRecurring !== $meetingRecurring) {
            $this->meetingRecurring = $meetingRecurring;
            if (null !== $meetingRecurring) {
                $meetingRecurring->addMeeting($this);
            }
        }

        return $this;
    }

    /**
     * @return MeetingRecurring
     */
    public function getMeetingRecurring()
    {
        return $this->meetingRecurring;
    }

    /**
     * Add task
     *
     * @param Task $task
     *
     * @return Meeting
     */
    public function addTask(Task $task)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setMeeting($this);
        }

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param Task $task
     *
     * @return $this
     */
    public function removeTask(Task $task)
    {
        if ($this->tasks->removeElement($task)) {
            $task->setMeeting(null);
        }

        return $this;
    }

    /**
     * Get tasks
     *
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @return Agenda[]
     */
    public function getAgendas()
    {
        return $this->agendas;
    }

    /**
     * @param Agenda[] $agendas
     *
     * @return $this
     */
    public function setAgendas(array $agendas)
    {
        $this->agendas->clear();
        foreach ($agendas as $agenda) {
            $this->addAgenda($agenda);
        }

        return $this;
    }

    /**
     * @param Agenda $agenda
     *
     * @return $this
     */
    public function addAgenda(Agenda $agenda)
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas->add($agenda);
            $agenda->setMeeting($this);
        }

        return $this;
    }

    /**
     * @param Agenda $agenda
     *
     * @return $this
     */
    public function removeAgenda(Agenda $agenda)
    {
        if ($this->agendas->removeElement($agenda)) {
            $agenda->setMeeting(null);
        }

        return $this;
    }

    /**
     * Set protocol
     *
     * @param Protocol $protocol
     * @return Meeting
     */
    public function setProtocol(Protocol $protocol = null)
    {
        if ($this->protocol !== $protocol) {
            $this->protocol = $protocol;
            $protocol->setMeeting($this);
        }

        return $this;
    }

    /**
     * Get protocol
     *
     * @return Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Add files
     *
     * @param File $file
     *
     * @return Meeting
     */
    public function addFile(File $file)
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setMeeting($this);
        }

        return $this;
    }

    /**
     * Remove file
     *
     * @param File $file
     *
     * @return $this
     */
    public function removeFile(File $file)
    {
        if ($this->files->removeElement($file)) {
            $file->setMeeting(null);
        }

        return $this;
    }

    /**
     * Get files
     *
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set prev
     *
     * @param Meeting $prev
     *
     * @return Meeting
     */
    public function setPrev(Meeting $prev = null)
    {
        if ($this->prev !== $prev) {
            $this->prev = $prev;
            if (null !== $prev) {
                $prev->setNext($this);
            }
        }

        return $this;
    }

    /**
     * Get prev
     *
     * @return Meeting
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Set next
     *
     * @param Meeting $next
     *
     * @return Meeting
     */
    public function setNext(Meeting $next = null)
    {
        if ($this->next !== $next) {
            $this->next = $next;
            if (null !== $next) {
                $next->setPrev($this);
            }
        }

        return $this;
    }

    /**
     * Get next
     *
     * @return Meeting
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @return boolean
     */
    public function isTemp()
    {
        return $this->temp;
    }

    /**
     * @param boolean $temp
     *
     * @return self
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSendInvitations()
    {
        return $this->sendInvitations;
    }

    /**
     * @param boolean $sendInvitations
     *
     * @return $this
     */
    public function setSendInvitations($sendInvitations)
    {
        $this->sendInvitations = $sendInvitations;

        return $this;
    }
}
