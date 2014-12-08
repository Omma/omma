<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingAgendaTest extends AbstractAuthenticatedTest
{

    public function testAgenda()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "AgendaMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/agendas.json", array(
            "name" => "AgendaText",
            "sorting_order" => 1,
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $agenda = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/agendas/" . $agenda->getId() . ".json");

        $newAgenda = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");

        $newMeeting = $newAgenda->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\Agenda', $newAgenda);

        $this->assertSame($agenda->getId(), $newAgenda->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame("AgendaText", $newAgenda->getName());
        $this->assertSame(1, $newAgenda->getSortingOrder());
    }
}
