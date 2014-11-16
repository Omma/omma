<?php

namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="meetings")
     * @ORM\JoinTable(name="omma_meeting_users")
     */
    protected $users;

    /**
     * @var \Application\Sonata\UserBundle\Entity\Group
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\Group", inversedBy="meetings")
     * @ORM\JoinTable(name="omma_meeting_groups")
     */
    protected $groups;

    /**
     * @var MeetingRecurring
     *
     * @ORM\OneToMany(targetEntity="MeetingRecurring", mappedBy="meetingId", orphanRemoval=true)
     */
    protected $meetingRecurrings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="meeting", orphanRemoval=true)
     */
    protected $tasks;

    /**
     * @var Agenda
     *
     * @ORM\OneToOne(targetEntity="Agenda", mappedBy="meeting", orphanRemoval=true)
     */
    protected $agenda;

    /**
     * @var Protocol
     *
     * @ORM\OneToOne(targetEntity="Protocol", mappedBy="meeting", orphanRemoval=true)
     */
    protected $protocol;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="meeting", orphanRemoval=true)
     */
    protected $files;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var Meeting
     *
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="next")
     * @ORM\JoinColumn(name="prev", referencedColumnName="id")
     */
    protected $prev;

    /**
     * @var Meeting
     *
     * @ORM\OneToOne(targetEntity="Meeting", mappedBy="prev")
     */
    protected $next;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime")
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime")
     */
    protected $dateEnd;
}
