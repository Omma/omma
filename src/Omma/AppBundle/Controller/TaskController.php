<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Task;
use Omma\AppBundle\Form\Type\MeetingTaskForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class TaskController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction()
    {
        return $this->get("omma.app.manager.task")->findBy(array(
            "user" => $this->getUser()
        ));
    }

    public function getAction(Task $task)
    {
        return $task;
    }

    /**
     * @Security("is_granted('edit', task)")
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Task $task)
    {
        return $this->processForm($request, $task);
    }

    /**
     * @Security("is_granted('edit', task)")
     *
     * @param Task $task
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Task $task)
    {
        $this->get("omma.app.manager.task")->delete($task);

        return $this->view("");
    }

    protected function processForm(Request $request, Task $task)
    {
        $new = null === $task->getId();
        $form = $this->createForm(new MeetingTaskForm(), $task, array(
            "method"          => $new ? "POST" : "PUT",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.task")->save($task);

            return $this->view($task);
        }

        return $this->view($form, 400);
    }
}
