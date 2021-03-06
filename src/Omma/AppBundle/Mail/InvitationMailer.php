<?php
namespace Omma\AppBundle\Mail;

use Omma\AppBundle\Entity\Attendee;
use Omma\AppBundle\Entity\AttendeeEntityManager;
use Omma\AppBundle\Entity\Meeting;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Mailer for sending invitation mails to users
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class InvitationMailer
{

    /**
     * @var AbstractMailer
     */
    private $mailer;

    /**
     * @var AttendeeEntityManager
     */
    protected $attendeeEntityManager;

    public function __construct(AbstractMailer $mailer, AttendeeEntityManager $attendeeEntityManager)
    {
        $this->mailer = $mailer;
        $this->attendeeEntityManager = $attendeeEntityManager;
    }

    public function sentInvitations(Meeting $meeting)
    {
        foreach ($meeting->getAttendees() as $attendee) {
            $this->sentInvitationToAttendee($attendee);
        }
    }

    public function sentInvitationToAttendee(Attendee $attendee)
    {
        // don't send invitation to owner
        if ($attendee->isOwner()) {
            return;
        }
        if (null !== $attendee->getInvitationSentAt()) {
            return;
        }
        $url = $this->mailer->getUrlGenerator()->generate(
            "omma_meeting_details",
            array("meeting" => $attendee->getMeeting()->getId()),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $context = array(
            "meetingUrl" => $url,
            "meeting"    => $attendee->getMeeting(),
        );
        $attendee->setInvitationSentAt(new \DateTime("now"));
        $this->mailer->sendMessageToUser(
            "OmmaAppBundle:Meeting:invitation_mail.txt.twig",
            $context,
            $attendee->getUser()
        );
        $this->attendeeEntityManager->save($attendee);
    }
}
