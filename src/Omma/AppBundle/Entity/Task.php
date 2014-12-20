<?php
namespace Omma\AppBundle\Entity;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Omma\AppBundle\Entity\Meeting;

/**
 * Task
 *
 * @ORM\Table("omma_task")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class Task extends Base
{
    const STATUS_OPEN = "open";

    const STATUS_CLOSED = "closed";

    const STATUS_IN_PROGESS = "in_progress";

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="tasks")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Agenda", inversedBy="task")
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     *
     * @var Agenda
     */
    protected $agenda;

    /**
     * @ORM\Column(name="task", type="string", length=255)
     *
     * @var string
     */
    protected $task;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(name="date", type="datetime")
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * @ORM\Column(name="priority", type="integer")
     *
     * @var integer Higher value means higher priority
     */
    protected $priority;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $status;

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
     * Set task
     *
     * @param string $task
     * @return Task
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Task
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
     * Set date
     *
     * @param \DateTime $date
     * @return Task
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
     * Set priority
     *
     * @param integer $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set meeting
     *
     * @param Meeting $meeting
     * @return Task
     */
    public function setMeeting(Meeting $meeting = null)
    {
        if ($this->meeting !== $meeting) {
            $this->meeting = $meeting;
            if (null !== $meeting) {
                $meeting->addTask($this);
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
     * Set user
     *
     * @param User $user
     * @return Task
     */
    public function setUser(User $user = null)
    {
        if ($this->user !== $user) {
            $this->user = $user;
            if (null !== $user) {
                $user->addTask($this);
            }
        }

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set agenda
     *
     * @param Agenda $agenda
     *
     * @return $this
     */
    public function setAgenda(Agenda $agenda = null)
    {
        if ($this->agenda !== $agenda) {
            $this->agenda = $agenda;
            if (null !== $agenda) {
                $agenda->setTask($this);
            }
        }

        return $this;
    }

    /**
     * Get agenda
     *
     * @return Agenda
     */
    public function getAgenda()
    {
        return $this->agenda;
    }
}
