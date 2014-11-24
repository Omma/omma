<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingForm;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction()
    {
        return $this->get("omma.app.manager.meeting")->findAll();
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\Form\Form
     */
    public function cpostAction(Request $request)
    {
        return $this->processForm($request, new Meeting());
    }

    /**
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting)
    {
        return $this->processForm($request, $meeting);
    }

    public function deleteAction(Meeting $meeting)
    {
        $this->get("omma.app.manager.meeting")->delete($meeting);

        return $this->view("");
    }

    /**
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function processForm(Request $request, Meeting $meeting)
    {
        $new = null === $meeting->getId();
        $form = $this->createForm(new MeetingForm(), $meeting, array(
            "method"          => $new ? "POST" : "PUT",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.meeting")->save($meeting);

            return $this->view($meeting);
        }

        return $this->view($form, 400);
    }

    public function getAction(Meeting $meeting)
    {
        return $meeting;
    }
}
