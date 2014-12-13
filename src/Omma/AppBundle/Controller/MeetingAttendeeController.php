<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Attendee;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingAttendeeForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @RouteResource("Attendee")
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAttendeeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     *
     * @return \Omma\AppBundle\Entity\Attendee[]
     */
    public function cgetAction(Meeting $meeting)
    {
        return $meeting->getAttendees();
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cpostAction(Request $request, Meeting $meeting)
    {
        $attendee = new Attendee();
        $attendee->setMeeting($meeting);

        return $this->processForm($request, $attendee);
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request  $request
     * @param Meeting  $meeting
     * @param Attendee $attendee
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting, Attendee $attendee)
    {
        return $this->processForm($request, $attendee);
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting  $meeting
     * @param Attendee $attendee
     *
     * @return Attendee
     */
    public function getAction(Meeting $meeting, Attendee $attendee)
    {
        return $attendee;
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Meeting  $meeting
     * @param Attendee $attendee
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Meeting $meeting, Attendee $attendee)
    {
        if ($attendee->isOwner()) {
            return $this->view("owner can't be removed", Response::HTTP_BAD_REQUEST);
        }
        $meeting->removeAttendee($attendee);
        $this->get("omma.app.manager.attendee")->delete($attendee);

        return $this->view("");
    }

    /**
     * @param Request  $request
     * @param Attendee $attendee
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function processForm(Request $request, Attendee $attendee)
    {
        $new = null === $attendee->getId();
        $form = $this->createForm(new MeetingAttendeeForm(), $attendee, array(
            "method"          => $new ? "POST" : "PUT",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.attendee")->save($attendee);

            return $this->view($attendee);
        }

        return $this->view($form, 400);
    }
}
