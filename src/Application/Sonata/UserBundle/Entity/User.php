<?php
/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Sonata\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Omma\AppBundle\Entity\Attendee;
use Omma\AppBundle\Entity\Task;
use Omma\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 * working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @ORM\Entity()
 * @ORM\Table(name="fos_user_user")
 *
 * @author Adrian Woeltche
 */
class User extends BaseUser
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Omma\AppBundle\Entity\Attendee", mappedBy="user")
     *
     * @var Attendee
     */
    protected $attendees;

    /**
     * @ORM\OneToMany(targetEntity="\Omma\AppBundle\Entity\Task", mappedBy="user")
     *
     * @var Task
     */
    protected $tasks;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->attendees = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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
     * Add attendees
     *
     * @param Attendee $attendee
     * @return $this
     */
    public function addAttendee(Attendee $attendee)
    {
        if (! $this->attendees->contains($attendee)) {
            $this->attendees->add($attendee);
            $attendee->setUser($this);
        }

        return $this;
    }

    /**
     * Remove attendees
     *
     * @param Attendee $attendee
     *
     * @return $this
     */
    public function removeAttendee(Attendee $attendee)
    {
        if ($this->attendees->removeElement($attendee)) {
            $attendee->setUser(null);
        }

        return $this;
    }

    /**
     * Get attendees
     *
     * @return Attendee[]
     */
    public function getAttendees()
    {
        return $this->attendees;
    }

    /**
     * Add tasks
     *
     * @param Task $task
     * @return User
     */
    public function addTask(Task $task)
    {
        if (! $this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
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
            $task->setUser(null);
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
}
