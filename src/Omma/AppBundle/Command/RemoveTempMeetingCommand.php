<?php
namespace Omma\AppBundle\Command;

use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Entity\MeetingEntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Remove temporary created meeting after 3 days.
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class RemoveTempMeetingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("omma:meeting:remove_temp")
            ->setDescription("Remove temporary meetings from db")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var MeetingEntityManager $meetingManager */
        $meetingManager = $this->getContainer()->get("omma.app.manager.meeting");
        $logger = $this->getContainer()->get("logger");

        $before = new \DateTime("-3days");
        $logger->info(sprintf("removing meetings before %s", $before->format("d.m.Y H:i:s")));
        /** @var Meeting[] $meetings */
        $meetings = $meetingManager->createQueryBuilder("m")
            ->select("m")
            ->where("m.temp = 1 AND m.created < :before")
            ->setParameter("before", $before)
            ->getQuery()
            ->getResult()
        ;
        foreach ($meetings as $meeting) {
            $logger->warning(sprintf("remove meeting %d", $meeting->getId()));
            $meetingManager->delete($meeting, false);
        }
        $meetingManager->flush();
    }
}
