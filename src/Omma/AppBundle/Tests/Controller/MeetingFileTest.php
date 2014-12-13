<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingFileTest extends AbstractAuthenticatedTest
{

    public function testFile()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "FileMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/files.json", array(
            "type" => true,
            "url" => "./"
        ));

        $file = $serializer->deserialize($content, 'Omma\AppBundle\Entity\File', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/files/" . $file->getId() . ".json");

        $newFile = $serializer->deserialize($content, 'Omma\AppBundle\Entity\File', "json");

        $newMeeting = $newFile->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\File', $newFile);

        $this->assertSame($file->getId(), $newFile->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame(true, $newFile->getType());
        $this->assertSame("./", $newFile->getUrl());

        $this->pushContent("/meetings/" . $newMeeting->getId() . "/files/" . $newFile->getId() . ".json", array(), "DELETE");

        $content = $this->fetchContent("/meetings/" . $newMeeting->getId() . "/files/" . $newFile->getId() . ".json", "GET", false, false);
    }
}
