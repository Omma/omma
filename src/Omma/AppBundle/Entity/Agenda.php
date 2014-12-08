<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="agendas")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\OneToOne(targetEntity="Task", mappedBy="agenda")
     *
     * @var Task
     */
    private $task;

    /**
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="parent", cascade={"remove"})
     *
     * @var ArrayCollection
     */
    private $subItems;

    /**
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="subItems")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     *
     * @var Agenda
     */
    private $parent;

    /**
     * @ORM\Column(name="item", type="string", length=255)
     * @NotBlank()
     *
     * @var string
     */
    private $item;

    /**
     * @ORM\Column(name="sortingOrder", type="integer")
     * @NotBlank()
     *
     * @var integer
     */
    private $sortingOrder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subItems = new ArrayCollection();
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
     * Set item
     *
     * @param string $item
     * @return Agenda
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set sortingOrder
     *
     * @param integer $sortingOrder
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
     * Add subItems
     *
     * @param Agenda $subItem
     *
     * @return Agenda
     */
    public function addSubItem(Agenda $subItem)
    {
        if (!$this->subItems->contains($subItem)) {
            $this->subItems->add($subItem);
            $subItem->setParent($this);
        }

        return $this;
    }

    /**
     * Remove subItems
     *
     * @param Agenda $subItems
     *
     * @return $this
     */
    public function removeSubItem(Agenda $subItems)
    {
        if ($this->subItems->removeElement($subItems)) {
            $subItems->setParent(null);
        }

        return $this;
    }

    /**
     * Get subItems
     *
     * @return Agenda[]
     */
    public function getSubItems()
    {
        return $this->subItems;
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
                $parent->removeSubItem($this);
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
