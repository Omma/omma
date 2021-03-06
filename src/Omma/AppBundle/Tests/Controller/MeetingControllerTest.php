<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingControllerTest extends AbstractAuthenticatedTest
{

    public function testCgetAdmin()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "TestMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $this->fetchContent("/meetings.json");
    }

    public function testCgetUser()
    {
        $this->login("test");
        $content = $this->pushContent("/meetings.json", array(
            "name" => "TestMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $this->fetchContent("/meetings.json");
    }

    public function testGetAdmin()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "TestMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Meeting', $meeting);

        $this->fetchContent("/meetings/" . $meeting->getId() . ".json");
    }

    public function testCpost()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "TestMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Meeting', $meeting);

        $this->assertSame("TestMeeting", $meeting->getName());

        $dateStart = $meeting->getDateStart()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 08:00:00", $dateStart);

        $dateEnd = $meeting->getDateEnd()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 09:30:00", $dateEnd);

        $newContent = $this->fetchContent("/meetings/" . $meeting->getId() . ".json");

        $newMeeting = $serializer->deserialize($newContent, 'Omma\AppBundle\Entity\Meeting', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Meeting', $newMeeting);

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame("TestMeeting", $newMeeting->getName());

        $dateStart = $newMeeting->getDateStart()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 08:00:00", $dateStart);

        $dateEnd = $newMeeting->getDateEnd()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 09:30:00", $dateEnd);
    }

    public function testCpostInvalid()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "InvalidFormMeeting"
        ), "POST", false, false);
    }

    public function testPut()
    {
        $this->login("test");

        $content = $this->pushContent("/meetings.json", array(
            "name" => "EditMeeting",
            "date_start" => "2014-01-01 09:45:00",
            "date_end" => "2014-01-01 11:15:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Meeting', $meeting);

        $meeting->setName("EditedMeeting");
        $meeting->setDateStart(\DateTime::createFromFormat("Y-m-d H:i:s", "2014-01-01 11:30:00"));
        $meeting->setDateEnd(\DateTime::createFromFormat("Y-m-d H:i:s", "2014-01-01 13:00:00"));

        $id = $meeting->getId();

        $editedDateStart = $meeting->getDateStart()->format("Y-m-d H:i:s");
        $editedDateEnd = $meeting->getDateEnd()->format("Y-m-d H:i:s");

        $editedContent = $this->pushContent("/meetings/" . $id . ".json", array(
            "name" => $meeting->getName(),
            "date_start" => $editedDateStart,
            "date_end" => $editedDateEnd
        ), "PUT");

        $editedMeeting = $serializer->deserialize($editedContent, 'Omma\AppBundle\Entity\Meeting', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Meeting', $editedMeeting);

        $this->assertSame("EditedMeeting", $editedMeeting->getName());

        $dateStart = $editedMeeting->getDateStart()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 11:30:00", $dateStart);

        $dateEnd = $editedMeeting->getDateEnd()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 13:00:00", $dateEnd);
    }

    public function testDelete()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "DeleteMeeting",
            "date_start" => "2014-01-01 14:00:00",
            "date_end" => "2014-01-01 15:30"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $this->assertInstanceOf('Omma\AppBundle\Entity\Meeting', $meeting);

        $id = $meeting->getId();
        $deletedContent = $this->pushContent("/meetings/" . $id . ".json", array(), "DELETE");

        $this->fetchContent("/meetings/" . $id . ".json", "GET", false, false);
    }
}
