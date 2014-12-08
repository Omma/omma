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
        $content = $this->pushContent("/meetings", array(
            "name" => "AgendaMeeting",
            "dateStart" => "2014-01-01 08:00:00",
            "dateEnd" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/agendas", array(
            "item" => "AgendaText",
            "sortingOrder" => 1
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $agenda = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/agendas/" . $agenda->getId());

        $newAgenda = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");

        $newMeeting = $newAgenda->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\Agenda', $newAgenda);

        $this->assertSame($agenda->getId(), $newAgenda->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame("AgendaText", $newAgenda->getItem());
        $this->assertSame(1, $newAgenda->getSortingOrder());
    }
}
