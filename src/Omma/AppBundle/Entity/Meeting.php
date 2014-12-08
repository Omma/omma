<?php
namespace Omma\AppBundle\Entity;

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
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Omma\AppBundle\Entity\Attendee", mappedBy="meeting")
     *
     * @var ArrayCollection
     */
    private $attendees;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\Group", inversedBy="meetings", orphanRemoval=true)
     * @ORM\JoinTable(name="omma_meeting_groups")
     *
     * @var \Application\Sonata\UserBundle\Entity\Group
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="MeetingRecurring", mappedBy="meeting", orphanRemoval=true)
     *
     * @var MeetingRecurring
     */
    private $meetingRecurrings;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="meeting", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="meeting", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $agendas;

    /**
     * @ORM\OneToOne(targetEntity="Protocol", mappedBy="meeting", orphanRemoval=true)
     *
     * @var Protocol
     */
    private $protocol;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="meeting", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $files;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @NotBlank()
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="next")
     * @ORM\JoinColumn(name="prev", referencedColumnName="id")
     *
     * @var Meeting
     */
    private $prev;

    /**
     * @ORM\OneToOne(targetEntity="Meeting", mappedBy="prev")
     *
     * @var Meeting
     */
    private $next;

    /**
     * @ORM\Column(name="date_start", type="datetime")
     * @NotBlank()
     * @DateTime()
     *
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @ORM\Column(name="date_end", type="datetime")
     * @NotBlank()
     * @DateTime()
     *
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attendees = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->meetingRecurrings = new ArrayCollection();
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
     * @param \Omma\AppBundle\Entity\Protocol $protocol
     * @return Meeting
     */
    public function setProtocol(\Omma\AppBundle\Entity\Protocol $protocol = null)
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * Get protocol
     *
     * @return \Omma\AppBundle\Entity\Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Add files
     *
     * @param \Omma\AppBundle\Entity\File $files
     * @return Meeting
     */
    public function addFile(\Omma\AppBundle\Entity\File $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \Omma\AppBundle\Entity\File $files
     */
    public function removeFile(\Omma\AppBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
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
