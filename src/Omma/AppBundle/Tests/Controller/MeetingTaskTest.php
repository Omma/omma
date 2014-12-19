<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Entity\Task;
use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingTaskTest extends AbstractAuthenticatedTest
{

    public function testTask()
    {
        $content = $this->pushContent("/meetings.json", array(
            "name" => "TaskMeeting",
            "date_start" => "2014-01-01 08:00:00",
            "date_end" => "2014-01-01 09:30:00"
        ));

        $serializer = $this->getContainer()->get("jms_serializer");
        $meeting = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Meeting', "json");

        $content = $this->pushContent("/meetings/" . $meeting->getId() . "/tasks.json", array(
            "task" => "Task",
            "description" => "Description",
            "type" => 1,
            "date" => "2014-01-02 08:00:00",
            "priority" => 1,
            "status" => Task::STATUS_OPEN,
        ));

        $task = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Task', "json");

        $content = $this->fetchContent("/meetings/" . $meeting->getId() . "/tasks/" . $task->getId() . ".json");

        $newTask = $serializer->deserialize($content, 'Omma\AppBundle\Entity\Task', "json");

        $newMeeting = $newTask->getMeeting();

        $this->assertInstanceOf('Omma\AppBundle\Entity\Task', $newTask);

        $this->assertSame($task->getId(), $newTask->getId());

        $this->assertSame($meeting->getId(), $newMeeting->getId());

        $this->assertSame("Task", $newTask->getTask());
        $this->assertSame("Description", $newTask->getDescription());
        $this->assertEquals(1, $newTask->getType());

        $date = $newTask->getDate()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-02 08:00:00", $date);

        $this->assertSame(1, $newTask->getPriority());
        $this->assertSame(Task::STATUS_OPEN, $newTask->getStatus());

        $this->pushContent("/meetings/" . $newMeeting->getId() . "/tasks/" . $newTask->getId() . ".json", array(), "DELETE");

        $content = $this->fetchContent("/meetings/" . $newMeeting->getId() . "/tasks/" . $newTask->getId() . ".json", "GET", false, false);
    }
}
