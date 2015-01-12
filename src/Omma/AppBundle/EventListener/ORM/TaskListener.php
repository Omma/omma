<?php
namespace Omma\AppBundle\EventListener\ORM;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Omma\AppBundle\Entity\Task;
use Omma\AppBundle\Mail\TaskMailer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Listener for sending notifications about added tasks to the assigned users.
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class TaskListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return TaskMailer
     */
    public function getTaskMailer()
    {
        return $this->container->get("omma.app.mail.task");
    }

    /**
     * send task notification for new tasks
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $task = $eventArgs->getEntity();
        if (!$task instanceof Task) {
            return;
        }
        if (null === $task->getUser()) {
            return;
        }
        $this->getTaskMailer()->sendNotificationToUser($task);
    }

    /**
     * Send task notifcation when user has changed
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $task = $eventArgs->getEntity();
        if (!$task instanceof Task) {
            return;
        }
        if (null === $task->getUser()) {
            return;
        }
        $uow = $eventArgs->getEntityManager()->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($task);
        // changed user
        if (isset($changeSet['user'])) {
            $this->getTaskMailer()->sendNotificationToUser($task);
        }
    }
}
