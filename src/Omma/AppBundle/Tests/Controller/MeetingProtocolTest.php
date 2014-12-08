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
        $content = $this->pushContent("/meetings", array(
            "name" => "ProtocolMeeting",
            "dateStart" => "2014-01-01 08:00:00",
            "dateEnd" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/protocols", array(
            "text" => "ProtocolText",
            "final" => false
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $protocol = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Protocol', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/protocols/" . $protocol->getId());

        $newProtocol = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Protocol', "json");

        $newMeeting = $newProtocol->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\Protocol', $newProtocol);

        $this->assertSame($protocol->getId(), $newProtocol->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame("ProtocolText", $newProtocol->getText());
    }
}
