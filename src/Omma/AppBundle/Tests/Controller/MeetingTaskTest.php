<?php
namespace Omma\AppBundle\Tests\Controller;

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
            "type" => "TaskType",
            "date" => "2014-01-02 08:00:00",
            "priority" => 1,
            "status" => 0
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
        $this->assertSame("TaskType", $newTask->getType());

        $date = $newTask->getDate()->format("Y-m-d H:i:s");
        $this->assertSame("2014-01-02 08:00:00", $date);

        $this->assertSame(1, $newTask->getPriority());
        $this->assertSame(0, $newTask->getStatus());

        $this->pushContent("/meetings/" . $newMeeting->getId() . "/tasks/" . $newTask->getId() . ".json", array(), "DELETE");

        $content = $this->fetchContent("/meetings/" . $newMeeting->getId() . "/tasks/" . $newTask->getId() . ".json", "GET", false, false);
    }
}
