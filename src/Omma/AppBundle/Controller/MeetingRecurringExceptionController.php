<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\MeetingRecurring;
use Omma\AppBundle\Entity\Meeting;
use Symfony\Component\HttpFoundation\Request;
use Omma\AppBundle\Entity\MeetingRecurringException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Omma\AppBundle\Form\Type\MeetingRecurringExceptionForm;

/**
 * @RouteResource("RecurringException")
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringExceptionController extends FOSRestController implements ClassResourceInterface
{

    public function cgetAction(Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        return $this->get("omma.app.manager.meeting_recurring_exception")
            ->createQueryBuilder("e")
            ->select("e")
            ->innerJoin("e.meetingRecurring", "r")
            ->where("r.meeting = :meeting")
            ->andWhere("e.meetingRecurring = :meetingRecurring")
            ->setParameter("meeting", $meeting)
            ->setParameter("meetingRecurring", $meetingRecurring)
            ->getQuery()
            ->getResult();
    }

    public function cpostAction(Request $request, Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        if ($meeting !== $meetingRecurring->getMeeting()) {
            throw new InvalidOptionsException("Meeting and MeetingRecurring are not linked", 500);
        }

        $meetingRecurringException = new MeetingRecurringException();
        $meetingRecurringException->setMeetingRecurring($meetingRecurring);

        return $this->processForm($request, $meetingRecurringException);
    }

    public function getAction(Meeting $meeting, MeetingRecurring $meetingRecurring, MeetingRecurringException $meetingRecurringException)
    {
        return $meetingRecurringException;
    }

    public function putAction(Request $request, Meeting $meeting, MeetingRecurring $meetingRecurring, MeetingRecurringException $meetingRecurringException)
    {
        return $this->processForm($request, $meetingRecurringException);
    }

    public function deleteAction(Meeting $meeting, MeetingRecurring $meetingRecurring, MeetingRecurringException $meetingRecurringException)
    {
        $this->get("omma.app.manager.meeting_recurring_exception")->delete($meetingRecurringException);

        return $this->view("");
    }

    protected function processForm(Request $request, MeetingRecurringException $meetingRecurringException)
    {
        $new = null === $meetingRecurringException->getId();
        $form = $this->createForm(new MeetingRecurringExceptionForm(), $meetingRecurringException, array(
            "method" => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.meeting_recurring_exception")->save($meetingRecurringException);

            return $this->view($meetingRecurringException);
        }

        return $this->view($form, 400);
    }
}
