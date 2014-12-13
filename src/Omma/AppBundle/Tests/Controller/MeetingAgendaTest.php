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
            "sorting_order" => 1
        ));

        $agenda = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/agendas/" . $agenda->getId() . ".json");

        $newAgenda = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");

        $newMeeting = $newAgenda->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\Agenda', $newAgenda);

        $this->assertSame($agenda->getId(), $newAgenda->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame("AgendaText", $newAgenda->getName());
        $this->assertSame(1, $newAgenda->getSortingOrder());

        $this->pushContent("/meetings/" . $newMeeting->getId() . "/agendas/" . $newAgenda->getId() . ".json", array(), "DELETE");

        $content = $this->fetchContent("/meetings/" . $newMeeting->getId() . "/agendas/" . $newAgenda->getId() . ".json", "GET", false, false);
    }

    public function testTree()
    {
        $this->markTestSkipped("not working, check later");

        $content = $this->pushContent("/meetings.json", array(
            "name"       => "AgendaMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end"   => "2014-01-01 09:30:00"
        ));
        $serializer = $this->getContainer()->get("jms_serializer");

        /** @var Meeting $meeting */
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");
        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/agendas.json", array(
            "name"     => "root",
            "children" => array(
                array(
                    "name"          => "test1",
                    "sorting_order" => 1,
                    "children"      => array(
                        array(
                            "name"          => "foo",
                            "sorting_order" => 1,
                        )
                    ),
                ),
                array(
                    "name"          => "test2",
                    "sorting_order" => 2,
                    "children"      => array(
                        array(
                            "name"          => "bar",
                            "sorting_order" => 1,
                        ),
                        array(
                            "name"          => "baz",
                            "sorting_order" => 2,
                        )
                    ),
                )
            )
        ), "PUT");
        $root = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Agenda', "json");
        var_dump($root);
    }
}
