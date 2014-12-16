<?php
namespace Omma\AppBundle\EventListener\ORM;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Entity\MeetingRecurring;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingRecurringListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getMeetingEntityManager()
    {

    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $recurring = $eventArgs->getEntity();
        if (!$recurring instanceof MeetingRecurring) {
            return;
        }
        if (MeetingRecurring::TYPE_NONE === $recurring->getType()) {
            return;
        }

    }
}
