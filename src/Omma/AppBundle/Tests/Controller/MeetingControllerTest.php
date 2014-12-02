<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

class MeetingControllerTest extends AbstractAuthenticatedTest
{

    public function testCgetAdmin()
    {
        $this->fetchContent("/meetings");
    }

    public function testCgetUser()
    {
        $this->login("test");
        $this->fetchContent("/meetings");
    }

    public function testGetAdmin()
    {
        $this->fetchContent("/meetings/1");
    }

    public function testCpost()
    {
        $content = $this->pushContent("/meetings", array(
            "name" => "TestMeeting",
            "dateStart" => "2014-01-01 08:00:00",
            "dateEnd" => "2014-01-01 09:30:00"
        ));
        
        $this->serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $this->serializer->deserialize($content, "Omma\AppBundle\Entity\Meeting", "json");
        
        $this->assertInstanceOf("Omma\AppBundle\Entity\Meeting", $meeting);
        
        $this->assertSame("TestMeeting", $meeting->getName());
        
        $this->assertSame("2014-01-01 08:00:00", $meeting->getDateStart()
            ->format("Y-m-d H:i:s"));
        $this->assertSame("2014-01-01 09:30:00", $meeting->getDateEnd()
            ->format("Y-m-d H:i:s"));
    }
}
