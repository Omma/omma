<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Application\Sonata\UserBundle\Entity\User;

/**
 *
 * @ORM\Table("omma_attendee")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
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
     * @ORM\ManyToOne(targetEntity="Omma\AppBundle\Entity\Meeting", inversedBy="attendees")
     *
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="attendees")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    private $mandatory = true;

    /**
     * @ORM\Column(type="string")
     * @NotBlank()
     *
     * @var string
     */
    private $status = Attendee::STATUS_INVITED;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $message;

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
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
                $meeting->addAttendee($this);
            }
        }

        return $this;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        if ($this->user !== $user) {
            $this->user = $user;
            if (null !== $user) {
                $user->addAttendee($this);
            }
        }

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isMandatory()
    {
        return $this->mandatory;
    }

    /**
     *
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
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
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
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     *
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
