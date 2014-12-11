<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Application\Sonata\UserBundle\Entity\User;

/**
 *
 * @ORM\Table("omma_attendee")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"meeting", "user"}, message="app.attendee.already_exists")
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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Omma\AppBundle\Entity\Meeting", inversedBy="attendees")
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="attendees")
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $mandatory = true;

    /**
     * @ORM\Column(type="string")
     * @NotBlank()
     *
     * @var string
     */
    protected $status = Attendee::STATUS_INVITED;

    /**
     * Marks attendee as meeting owner
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $owner = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $message;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $invitationSentAt;

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
    public function setMeeting(Meeting $meeting = null)
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
     * @return boolean
     */
    public function isOwner()
    {
        return $this->owner;
    }

    /**
     * @param boolean $owner
     *
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        if ($owner) {
            $this->setStatus(self::STATUS_ACCEPTED);
        }

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

    /**
     * @return \DateTime
     */
    public function getInvitationSentAt()
    {
        return $this->invitationSentAt;
    }

    /**
     * @param \DateTime $invitationSentAt
     *
     * @return $this
     */
    public function setInvitationSentAt($invitationSentAt)
    {
        $this->invitationSentAt = $invitationSentAt;

        return $this;
    }
}
