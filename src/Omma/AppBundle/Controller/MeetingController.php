<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Attendee;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingForm;
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
     * @Security("is_fully_authenticated()")
     */
    public function cgetAction()
    {
        $user = $this->getUser();
        if ($this->get("security.context")->isGranted("ROLE_SUPER_ADMIN")) {
            return $this->get("omma.app.manager.meeting")->findAll();
        } else {
            $query = $this->get("omma.app.manager.meeting")->createQueryBuilder("m");
            $query->select("m")
                ->innerJoin("m.users", "u")
                ->where("u.id = :userId")
                ->setParameter("userId", $user->getId());

            return $query->getQuery()->getResult();
        }
    }

    /**
     * @Security("is_fully_authenticated()")
     *
     * @param \DateTime $dateStart
     *            Start Date
     * @param \DateTime $dateEnd
     *            End Date
     *
     * @return array
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
            $query->innerJoin("m.users", "u")
                ->andWhere("u.id = :userId")
                ->setParameter("userId", $user->getId());
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @Security("is_fully_authenticated()")
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
        $attendee->setMeeting($meeting)->setUser($user);

        return $this->processForm($request, $meeting);
    }

    /**
     * @Security("is_fully_authenticated() and is_granted('edit', meeting)")
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
     * @Security("is_fully_authenticated() and is_granted('edit', meeting)")
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
     * @Security("is_fully_authenticated() and is_granted('edit', meeting)")
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
            $this->get("omma.app.manager.meeting")->save($meeting);

            return $this->view($meeting);
        }

        return $this->view($form, 400);
    }

    /**
     * @Security("is_fully_authenticated() and is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     *
     * @return Meeting
     */
    public function getAction(Meeting $meeting)
    {
        $view = $this->view($meeting);
        $view->setTemplate("OmmaAppBundle:Meeting:edit.html.twig")->setTemplateVar("meeting");

        return $this->handleView($view);
    }
}
