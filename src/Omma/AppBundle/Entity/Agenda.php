<?php
namespace Omma\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     *
     * @var Agenda
     */
    private $parent;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @NotBlank()
     *
     * @var string
     */
    private $name;

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
     * @param \Omma\AppBundle\Entity\Meeting $meeting
     *
     * @return Agenda
     */
    public function setMeeting(\Omma\AppBundle\Entity\Meeting $meeting)
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * Get meeting
     *
     * @return \Omma\AppBundle\Entity\Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set task
     *
     * @param \Omma\AppBundle\Entity\Task $task
     *
     * @return Agenda
     */
    public function setTask(\Omma\AppBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \Omma\AppBundle\Entity\Task
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
        $agenda->setParent($this);
        $this->children->add($agenda);

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
        $this->parent = $parent;

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
