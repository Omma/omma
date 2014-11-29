<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
        $this->subItems = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \Omma\AppBundle\Entity\Meeting $meeting
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
     * Add subItems
     *
     * @param \Omma\AppBundle\Entity\Agenda $subItems
     * @return Agenda
     */
    public function addSubItem(\Omma\AppBundle\Entity\Agenda $subItems)
    {
        $this->subItems[] = $subItems;

        return $this;
    }

    /**
     * Remove subItems
     *
     * @param \Omma\AppBundle\Entity\Agenda $subItems
     */
    public function removeSubItem(\Omma\AppBundle\Entity\Agenda $subItems)
    {
        $this->subItems->removeElement($subItems);
    }

    /**
     * Get subItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubItems()
    {
        return $this->subItems;
    }

    /**
     * Set parent
     *
     * @param \Omma\AppBundle\Entity\Agenda $parent
     * @return Agenda
     */
    public function setParent(\Omma\AppBundle\Entity\Agenda $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Omma\AppBundle\Entity\Agenda
     */
    public function getParent()
    {
        return $this->parent;
    }
}
