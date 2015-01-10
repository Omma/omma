<?php
namespace Omma\AppBundle\Mail;

use Omma\AppBundle\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Mailer for sending task notifications to users
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class TaskMailer
{
    /**
     * @var AbstractMailer
     */
    protected $mailer;

    public function __construct(AbstractMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNotificationToUser(Task $task)
    {
        $url = $this->mailer->getUrlGenerator()->generate(
            "omma_meeting_details",
            array("meeting" => $task->getMeeting()->getId()),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $context = array(
            "meetingUrl" => $url,
            "meeting"    => $task->getMeeting(),
            "task"       => $task,
        );
        $this->mailer->sendMessageToUser(
            "OmmaAppBundle:Task:notification_mail.txt.twig",
            $context,
            $task->getUser()
        );
    }
}
