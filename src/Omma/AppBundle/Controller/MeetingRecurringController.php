<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\MeetingRecurring;
use Omma\AppBundle\Entity\Meeting;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Recurring")
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringController extends FOSRestController implements ClassResourceInterface
{

    public function cgetAction(Meeting $meeting)
    {
        return $this->get("omma.app.manager.meeting_recurring")
            ->createQueryBuilder("r")
            ->select("r")
            ->where("r.meeting = :meeting")
            ->setParameter("meeting", $meeting)
            ->getQuery()
            ->getResult();
    }

    public function cpostAction(Request $request, Meeting $meeting)
    {
        $meetingRecurring = new MeetingRecurring();
        $meetingRecurring->setMeeting($meeting);
        $meeting->addMeetingRecurring($meetingRecurring);

        return $this->processForm($request, $meetingRecurring);
    }

    public function getAction(Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        return $meetingRecurring;
    }

    public function putAction(Request $request, Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        return $this->processForm($request, $meetingRecurring);
    }

    public function deleteAction(Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        $this->get("omma.app.manager.meeting_recurring")->delete($meetingRecurring);

        return $this->view("");
    }

    protected function processForm(Request $request, MeetingRecurring $meetingRecurring)
    {
        $new = null === $meetingRecurring->getId();
        $form = $this->createForm(new MeetingRecurringForm(), $protocol, array(
            "method" => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager_recurring")->save($meetingRecurring);

            return $this->view($meetingRecurring);
        }

        return $this->view($form, 400);
    }
}
