<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingProtocolTest extends AbstractAuthenticatedTest
{

    public function testProtocol()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "ProtocolMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/protocol.json", array(
            "text" => "ProtocolText",
            "final" => false
        ), "PUT");

        $protocol = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Protocol', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/protocol.json");

        $newProtocol = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Protocol', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Protocol', $newProtocol);

        $this->assertSame($protocol->getId(), $newProtocol->getId());

        $this->assertSame("ProtocolText", $newProtocol->getText());
        $this->assertSame(false, $newProtocol->isFinal());

        $this->pushContent("/meetings/" . $meeting->getId() . "/protocol.json", array(), "DELETE");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/protocol.json", "GET");
        $this->assertEquals("{}", $content);
    }
}
