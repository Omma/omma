<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Entity\Protocol;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Omma\AppBundle\Entity\Task;
use Omma\AppBundle\Form\Type\MeetingTaskForm;

/**
 *
 * @RouteResource("Task")
 *
 * @author Adrian Woeltche
 */
class MeetingTaskController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     *
     * @return Task[]
     */
    public function cgetAction(Meeting $meeting)
    {
        return $this->get("omma.app.manager.task")
            ->createQueryBuilder("t")
            ->select("t")
            ->where("t.meeting = :meeting")
            ->setParameter("meeting", $meeting)
            ->getQuery()
            ->getResult();
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cpostAction(Request $request, Meeting $meeting)
    {
        $task = new Task();
        $task->setMeeting($meeting);

        return $this->processForm($request, $task);
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     * @param Task $task
     *
     * @return Protocol
     */
    public function getAction(Meeting $meeting, Task $task)
    {
        return $task;
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     * @param Task $task
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting, Task $task)
    {
        return $this->processForm($request, $task);
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     * @param Task $task
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Meeting $meeting, Task $task)
    {
        $this->get("omma.app.manager.task")->delete($task);

        return $this->view("");
    }

    protected function processForm(Request $request, Task $task)
    {
        $new = null === $task->getId();
        $form = $this->createForm(new MeetingTaskForm(), $task, array(
            "method" => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.task")->save($task);

            return $this->view($task);
        }

        return $this->view($form, 400);
    }
}
