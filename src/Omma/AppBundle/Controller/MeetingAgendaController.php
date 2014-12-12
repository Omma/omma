<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Agenda;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingAgendaCollectionForm;
use Omma\AppBundle\Form\Type\MeetingAgendaForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @RouteResource("Agenda")
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAgendaController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     *
     * @return mixed|Agenda
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cgetAction(Meeting $meeting)
    {
        $root = $this->get("omma.app.manager.agenda")
            ->createQueryBuilder("a")
            ->select("a")
            ->where("a.meeting = :meeting AND a.parent IS NULL AND a.name = 'root'")
            ->setParameter("meeting", $meeting)
            ->orderBy("a.sortingOrder")
            ->getQuery()
            ->getOneOrNullResult();

        if (null !== $root) {
            return $root;
        }

        $root = new Agenda();
        $root
            ->setName("root")
            ->setMeeting($meeting)
        ;
        $this->get("omma.app.manager.agenda")->save($root);

        return $root;
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cputAction(Request $request, Meeting $meeting)
    {
        return $this->processTreeForm($request, $meeting);
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
        $agenda = new Agenda();
        $agenda->setMeeting($meeting);

        return $this->processForm($request, $agenda);
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     * @param Agenda  $agenda
     *
     * @return Agenda
     */
    public function getAction(Meeting $meeting, Agenda $agenda)
    {
        return $agenda;
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     * @param Agenda  $agenda
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting, Agenda $agenda)
    {
        return $this->processForm($request, $agenda);
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Meeting $meeting
     * @param Agenda  $agenda
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Meeting $meeting, Agenda $agenda)
    {
        $this->get("omma.app.manager.agenda")->delete($agenda);

        return $this->view("");
    }

    /**
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function processTreeForm(Request $request, Meeting $meeting)
    {
        $root = $this->cgetAction($meeting);
        $form = $this->get("form.factory")->createNamed("", "omma_meeting_agenda", $root, array(
            "method"          => "PUT",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $root->setMeetingRecursive($meeting);
            $this->get("omma.app.manager.agenda")->save($root);

            // remove orphans manually, as automatical removal will remove everything
            /** @var Agenda[] $agendas */
            $agendas = $this->get("omma.app.manager.agenda")->findBy(array(
                "meeting" => $meeting,
                "parent"  => null,
            ));
            foreach ($agendas as $agenda) {
                if ($agenda === $root) {
                    continue;
                }
                $this->get("omma.app.manager.agenda")->delete($agenda, false);
            }
            $this->get("omma.app.manager.agenda")->flush();

            return $this->view($root);
        }

        return $this->view($form, 400);
    }

    protected function processForm(Request $request, Agenda $agenda)
    {
        $new = null === $agenda->getId();
        $form = $this->get("form.factory")->createNamed("", new MeetingAgendaForm(), $agenda, array(
            "method"          => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.agenda")->save($agenda);

            return $this->view($agenda);
        }

        return $this->view($form, 400);
    }
}
