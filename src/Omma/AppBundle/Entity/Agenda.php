<?php

namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Meeting
     *
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="agenda")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     */
    protected $meeting;

    /**
     * @var Task
     *
     * @ORM\OneToOne(targetEntity="Task", mappedBy="agenda")
     */
    protected $task;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="parent")
     */
    protected $subItems;

    /**
     * @var Agenda
     *
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="subItems")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", length=255)
     */
    protected $item;

    /**
     * @var integer
     *
     * @ORM\Column(name="sortingOrder", type="integer")
     */
    protected $sortingOrder;
}
