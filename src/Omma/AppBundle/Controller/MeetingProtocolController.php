<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Entity\Protocol;
use Omma\AppBundle\Form\Type\MeetingProtocolForm;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @RouteResource("Protocol")
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 * @author Adrian Woeltche
 */
class MeetingProtocolController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     *
     * @return Protocol
     */
    public function getAction(Meeting $meeting)
    {
        $protocol = $meeting->getProtocol();
        if (null !== $protocol) {
            return $protocol;
        }
        $protocol = new Protocol();
        $protocol->setMeeting($meeting);

        return $protocol;
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting)
    {
        return $this->processForm($request, $this->getAction($meeting));
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
        if (null === $meeting->getProtocol()) {
            return $this->view("");
        }
        $this->get("omma.app.manager.protocol")->delete($meeting->getProtocol());

        return $this->view("");
    }

    protected function processForm(Request $request, Protocol $protocol)
    {
        if ($protocol->isFinal()) {
            return $this->view("marked as final", Response::HTTP_FORBIDDEN);
        }
        $form = $this->createForm(new MeetingProtocolForm(), $protocol, array(
            "method" => "PUT",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.protocol")->save($protocol);

            return $this->view($protocol);
        }

        return $this->view($form, 400);
    }
}
