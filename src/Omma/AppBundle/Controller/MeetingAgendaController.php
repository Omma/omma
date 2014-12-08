<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Agenda;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingAgendaForm;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @RouteResource("Agenda")
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAgendaController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(Meeting $meeting)
    {
        return $this->get("omma.app.manager.agenda")->createQueryBuilder("a")
            ->select("a")
            ->where("a.meeting = :meeting AND a.parent IS NULL")
            ->setParameter("meeting", $meeting)
            ->orderBy("a.sortingOrder")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Request $request
     * @param Meeting $meeting
     * @Put("/meetings/{meeting}/agendas/tree")
     */
    public function treeAction(Request $request, Meeting $meeting)
    {
        echo "foo";
        $tree = $this->get("serializer")->deserialize($request->getContent(), "Omma\AppBundle\Entity\Agenda", "json");
        var_dump($tree);
        return $this->view(null);
    }

    public function cpostAction(Request $request, Meeting $meeting)
    {
        $agenda = new Agenda();
        $agenda->setMeeting($meeting);

        return $this->processForm($request, $agenda);
    }

    public function getAction(Meeting $meeting, Agenda $agenda)
    {
        return $agenda;
    }

    public function putAction(Request $request, Meeting $meeting, Agenda $agenda)
    {
        return $this->processForm($request, $agenda);
    }

    public function deleteAction(Meeting $meeting, Agenda $agenda)
    {
        $this->get("omma.app.manager.agenda")->delete($agenda);

        return $this->view("");
    }

    protected function processForm(Request $request, Agenda $agenda)
    {
        $new = null === $agenda->getId();
        $form = $this->createForm(new MeetingAgendaForm(), $agenda, array(
            "method"          => $new ? "POST" : "PUT",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.agenda")->save($agenda);

            return $this->view($agenda);
        }

        return $this->view($form, 400);
    }
}
