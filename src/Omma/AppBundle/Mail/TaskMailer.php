<?php
namespace Omma\AppBundle\Mail;

use Omma\AppBundle\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 *
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
            array("task" => $task->getId()),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $context = array(
            "taskUrl" => $url,
            "meeting" => $task->getMeeting(),
            "task"    => $task,
        );
        $this->mailer->sendMessageToUser(
            "OmmaAppBundle:Task:notification_mail.txt.twig",
            $context,
            $task->getUser()
        );
    }
}
