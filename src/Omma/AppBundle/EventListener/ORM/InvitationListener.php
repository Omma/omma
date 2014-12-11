<?php
namespace Omma\AppBundle\EventListener\ORM;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Omma\AppBundle\Entity\Attendee;
use Omma\AppBundle\Entity\Meeting;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class InvitationListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        // can't set initation mailer directly, as this will produce a dependency cicle with doctrine
        $this->container = $container;
    }

    /**
     * @return \Omma\AppBundle\Mail\InvitationMailer
     */
    protected function getInvitationMailer()
    {
        return $this->container->get("omma.app.mail.invitation");
    }

    /**
     * Sent invite email to new attendees, for existing meetings
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $attendee = $eventArgs->getEntity();
        if (!$attendee instanceof Attendee) {
            return;
        }
        /*if (!$attendee->getMeeting()->isSendInvitations()) {
            return;
        }*/
        $this->getInvitationMailer()->sentInvitationToAttendee($attendee);
    }

    /**
     * Send invite emails, when
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $meeting = $eventArgs->getEntity();
        if (!$meeting instanceof Meeting) {
            return;
        }
        $uow = $eventArgs->getEntityManager()->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($meeting);

        // send mails when sendInvitations changed to true
        if (isset($changeSet['sendInvitations']) and $changeSet['sendInvitations'][1]) {
            $this->getInvitationMailer()->sentInvitations($meeting);
        }
    }
}
