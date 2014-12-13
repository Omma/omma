<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Attendee;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingConfirmationForm;
use Omma\AppBundle\Form\Type\MeetingForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 * @author Adrian Woeltche
 */
class MeetingController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @View(serializerEnableMaxDepthChecks=true)
     * @return Meeting[]
     */
    public function cgetAction(Request $request)
    {
        $user = $this->getUser();
        $query = $this->get("omma.app.manager.meeting")
            ->createQueryBuilder("m")
            ->select("m")
        ;

        if (!$this->get("security.context")->isGranted("ROLE_SUPER_ADMIN")) {
            $query
                ->innerJoin("m.attendees", "a")
                ->andWhere("a.meeting = m.id")
                ->innerJoin("a.user", "u")
                ->andWhere("u.id = :userId")
                ->setParameter("userId", $user->getId())
            ;
        }

        if (null !== ($search = $request->query->get("search"))) {
            $query
                ->andWhere("m.name LIKE :search")
                ->setParameter("search", "%" . $search . "%")
                ->orderBy("m.dateStart", "DESC")
            ;
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param \DateTime $dateStart
     *            Start Date
     * @param \DateTime $dateEnd
     *            End Date
     *
     * @return array
     *
     */
    public function getRangeAction(\DateTime $dateStart, \DateTime $dateEnd)
    {
        $user = $this->getUser();

        $query = $this->get("omma.app.manager.meeting")->createQueryBuilder("m");
        $query->select("m")
            ->where("m.dateStart BETWEEN :dateStart AND :dateEnd")
            ->andWhere("m.dateEnd BETWEEN :dateStart AND :dateEnd")
            ->setParameter("dateStart", $dateStart)
            ->setParameter("dateEnd", $dateEnd);

        if (! $this->get("security.context")->isGranted("ROLE_SUPER_ADMIN")) {
            $query->innerJoin("m.attendees", "a")
                ->andWhere("a.meeting = m.id")
                ->innerJoin("a.user", "u")
                ->andWhere("u.id = :userId")
                ->setParameter("userId", $user->getId());
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @return \Symfony\Component\Form\Form
     */
    public function cpostAction(Request $request)
    {
        $user = $this->getUser();

        $meeting = new Meeting();
        $attendee = new Attendee();

        $attendee
            ->setMeeting($meeting)
            ->setUser($user)
            ->setOwner(true)
        ;

        return $this->processForm($request, $meeting);
    }

    /**
     * @Security("is_granted('edit', meeting)")
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting)
    {
        return $this->processForm($request, $meeting);
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Meeting $meeting)
    {
        $this->get("omma.app.manager.meeting")->delete($meeting);

        return $this->view("");
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function processForm(Request $request, Meeting $meeting)
    {
        $new = null === $meeting->getId();
        $form = $this->createForm(new MeetingForm(), $meeting, array(
            "method" => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $meeting->setTemp(false);
            $this->get("omma.app.manager.meeting")->save($meeting);

            return $this->view($meeting);
        }

        return $this->view($form, 400);
    }

    /**
     * @Security("is_granted('view', meeting)")
     * @View(serializerEnableMaxDepthChecks=true)
     */
    public function getAction(Meeting $meeting)
    {
        return $meeting;
    }

    /**
     * @Security("is_granted('view', meeting)")
     * @Route("/meetings/{meeting}/details", name="omma_meeting_details")
     * @Template()
     *
     * @param Meeting $meeting
     *
     * @return Meeting
     */
    public function detailsAction(Request $request, Meeting $meeting)
    {
        $owner = $this->get("security.context")->isGranted("owner", $meeting);
        $canEdit = $this->get("security.context")->isGranted("edit", $meeting);

        $attendee = $this->get("omma.app.manager.attendee")->findOneBy(array(
            "meeting" => $meeting,
            "user"    => $this->getUser(),
            "owner"   => 0
        ));
        $attendeeForm = null;
        if (null !== $attendee) {
            $attendeeForm = $this->createForm(new MeetingConfirmationForm(), $attendee);
            $attendeeForm->handleRequest($request);
            if ($attendeeForm->isValid()) {
                $this->get("omma.app.manager.attendee")->save($attendee);

                return $this->redirect($this->generateUrl("omma_meeting_details", array("meeting" => $meeting->getId())));
            }
        }

        return array(
            "meeting"      => $meeting,
            "owner"        => $owner,
            "can_edit"     => $canEdit,
            "attendee"     => $attendee,
            "attendeeForm" => $attendeeForm ? $attendeeForm->createView() : null,
        );
    }

    /**
     * @Route("/meetings/create", name="omma_app_meeting_create")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction()
    {
        $user = $this->getUser();

        $meeting = new Meeting();
        $meeting
            ->setName("temp-" . date("Y-m-d"))
            ->setTemp(true)
            ->setDateStart(new \DateTime())
            ->setDateEnd(new \DateTime("+1hour"))
        ;
        $attendee = new Attendee();
        $attendee
            ->setMeeting($meeting)
            ->setUser($user)
            ->setOwner(true)
        ;

        $this->get("omma.app.manager.meeting")->save($meeting);

        return $this->redirect($this->generateUrl("omma_meeting_details", array("meeting" => $meeting->getId())));
    }
}
