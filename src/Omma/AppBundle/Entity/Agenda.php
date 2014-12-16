<?php
namespace Omma\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Omma\AppBundle\Entity\Task;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Agenda
 *
 * @ORM\Table("omma_agenda")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class Agenda extends Base
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
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="agendas")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * @ORM\OneToOne(targetEntity="Task", mappedBy="agenda")
     *
     * @var Task
     */
    protected $task;

    /**
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="parent", cascade={"persist", "remove"})
     *
     * @var ArrayCollection
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     *
     * @var Agenda
     */
    protected $parent;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @NotBlank()
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="sortingOrder", type="integer")
     * @NotBlank()
     *
     * @var integer
     */
    protected $sortingOrder = 1;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set sortingOrder
     *
     * @param integer $sortingOrder
     *
     * @return Agenda
     */
    public function setSortingOrder($sortingOrder)
    {
        $this->sortingOrder = $sortingOrder;

        return $this;
    }

    /**
     * Get sortingOrder
     *
     * @return integer
     */
    public function getSortingOrder()
    {
        return $this->sortingOrder;
    }

    /**
     * Set meeting
     *
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function setMeeting(Meeting $meeting)
    {
        if ($this->meeting !== $meeting) {
            $this->meeting = $meeting;
            if (null !== $meeting) {
                $meeting->addAgenda($this);
            }
        }

        return $this;
    }

    /**
     * Set meeting for all child agendas
     *
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function setMeetingRecursive(Meeting $meeting)
    {
        $this->setMeeting($meeting);
        foreach ($this->children as $child) {
            $child->setMeetingRecursive($meeting);
        }

        return $this;
    }

    /**
     * Get meeting
     *
     * @return $this
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set task
     *
     * @param Task $task
     *
     * @return Agenda
     */
    public function setTask(Task $task = null)
    {
        if ($this->task !== $task) {
            $task->setAgenda($this);
            if (null !== $task) {
                $this->task = $task;
            }
        }

        return $this;
    }

    /**
     * Get task
     *
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return Agenda[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param self[] $children
     *
     * @return $this
     */
    public function setChildren(array $children)
    {
        $this->children->clear();
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * @param Agenda $agenda
     *
     * @return $this
     */
    public function addChild(Agenda $agenda)
    {
        if (!$this->children->contains($agenda)) {
            $this->children->add($agenda);
            $agenda->setParent($this);
        }

        return $this;
    }

    /**
     * Remove child
     *
     * @param Agenda $agenda
     *
     * @return $this
     */
    public function removeChild(Agenda $agenda)
    {
        if ($this->children->removeElement($agenda)) {
            $agenda->setParent(null);
        }

        return $this;
    }

    /**
     * Set parent
     *
     * @param Agenda $parent
     *
     * @return Agenda
     */
    public function setParent(Agenda $parent = null)
    {
        if ($this->parent !== $parent) {
            $this->parent = $parent;
            if (null !== $parent) {
                $parent->addChild($this);
            }
        }

        return $this;
    }

    /**
     * Get parent
     *
     * @return Agenda
     */
    public function getParent()
    {
        return $this->parent;
    }
}
