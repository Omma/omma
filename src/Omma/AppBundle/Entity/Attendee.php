<?php
namespace Omma\AppBundle\Entity;

use Omma\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 *
 * @ORM\Table("omma_attendee")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class Attendee extends Base
{
    const STATUS_INVITED = "invited";
    const STATUS_ACCEPTED = "accepted";
    const STATUS_DECLIED = "declined";
    const STATUS_MAYBE = "maybe";

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @var Meeting
     * @ORM\ManyToOne(targetEntity="Omma\AppBundle\Entity\Meeting", inversedBy="attendees")
     */
    protected $meeting;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="meetings")
     */
    protected $user;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $mandatory = true;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @NotBlank()
     */
    protected $status = Attendee::STATUS_INVITED;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function setMeeting(Meeting $meeting)
    {
        $this->meeting = $meeting;
        $meeting->addAttendee($this);

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMandatory()
    {
        return $this->mandatory;
    }

    /**
     * @param boolean $mandatory
     *
     * @return $this
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
