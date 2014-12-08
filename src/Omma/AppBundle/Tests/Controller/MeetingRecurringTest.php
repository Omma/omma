<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;
use Omma\AppBundle\Entity\MeetingRecurring;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringTest extends AbstractAuthenticatedTest
{

    public function testMeetingRecurring()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "RecurringMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/recurrings.json", array(
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-12-31 09:30:00",
            "type" => MeetingRecurring::TYPE_WEEK,
            "recurring" => 1
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meetingRecurring = $serializer->deserialize($content, 'Omma\AppBundle\Entity\MeetingRecurring', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/recurrings/" . $meetingRecurring->getId() . ".json");

        $newMeetingRecurring = $serializer->deserialize($content, 'Omma\AppBundle\Entity\MeetingRecurring', "json");

        $newMeeting = $newMeetingRecurring->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\MeetingRecurring', $newMeetingRecurring);

        $this->assertSame($meetingRecurring->getId(), $newMeetingRecurring->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame(MeetingRecurring::TYPE_WEEK, $newMeetingRecurring->getType());
        $this->assertSame(1, $newMeetingRecurring->getRecurring());
    }
}
