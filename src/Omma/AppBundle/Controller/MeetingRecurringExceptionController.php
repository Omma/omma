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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @RouteResource("RecurringException")
 *
 * @author Adrian Woeltche
 */
class MeetingRecurringExceptionController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting          $meeting
     * @param MeetingRecurring $meetingRecurring
     *
     * @return MeetingRecurringException[]
     */
    public function cgetAction(Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        return $meetingRecurring->getMeetingRecurringExceptions();
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     * @param MeetingRecurring $meetingRecurring
     *
     * @throws InvalidOptionsException
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cpostAction(Request $request, Meeting $meeting, MeetingRecurring $meetingRecurring)
    {
        if (!$meetingRecurring->getMeetings()->contains($meeting)) {
            throw new InvalidOptionsException("Meeting and MeetingRecurring are not linked", 500);
        }

        $meetingRecurringException = new MeetingRecurringException();
        $meetingRecurringException->setMeetingRecurring($meetingRecurring);

        return $this->processForm($request, $meetingRecurringException);
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     * @param MeetingRecurring $meetingRecurring
     * @param MeetingRecurringException $meetingRecurringException
     *
     * @return MeetingRecurringException
     */
    public function getAction(Meeting $meeting, MeetingRecurring $meetingRecurring, MeetingRecurringException $meetingRecurringException)
    {
        return $meetingRecurringException;
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     * @param MeetingRecurring $meetingRecurring
     * @param MeetingRecurringException $meetingRecurringException
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting, MeetingRecurring $meetingRecurring, MeetingRecurringException $meetingRecurringException)
    {
        return $this->processForm($request, $meetingRecurringException);
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Meeting                   $meeting
     * @param MeetingRecurring          $meetingRecurring
     * @param MeetingRecurringException $meetingRecurringException
     *
     * @return \FOS\RestBundle\View\View
     */
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
