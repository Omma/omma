<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;
use Omma\AppBundle\Entity\MeetingRecurring;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringExceptionTest extends AbstractAuthenticatedTest
{

    public function testMeetingRecurringException()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "RecurringExceptionMeeting",
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

        $meetingRecurring = $serializer->deserialize($content, 'Omma\AppBundle\Entity\MeetingRecurring', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/recurrings/" . $meetingRecurring->getId() . "/recurringexceptions.json", array(
            "date" => "2014-01-01 08:00:00"
        ));

        $meetingRecurringException = $serializer->deserialize($content, 'Omma\AppBundle\Entity\MeetingRecurringException', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/recurrings/" . $meetingRecurring->getId() . "/recurringexceptions/" . $meetingRecurringException->getId() . ".json");

        $newMeetingRecurringException = $serializer->deserialize($content, 'Omma\AppBundle\Entity\MeetingRecurringException', "json");

        $newMeetingRecurring = $newMeetingRecurringException->getMeetingRecurring();

        $newMeeting = $newMeetingRecurring->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\MeetingRecurringException', $newMeetingRecurringException);

        $this->assertSame($meetingRecurringException->getId(), $newMeetingRecurringException->getId());

        $this->assertSame($meetingRecurring->getId(), $newMeetingRecurring->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $date = $newMeetingRecurringException->getDate()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-01 08:00:00", $date);
    }
}
