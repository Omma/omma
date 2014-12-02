<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Entity\Protocol;
use Omma\AppBundle\Form\Type\MeetingProtocolForm;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @RouteResource("Protocol")
 * 
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingProtocolController extends FOSRestController implements ClassResourceInterface
{

    public function cgetAction(Meeting $meeting)
    {
        return $this->get("omma.app.manager.protocol")
            ->createQueryBuilder("p")
            ->select("p")
            ->where("p.meeting = :meeting")
            ->setParameter("meeting", $meeting)
            ->getQuery()
            ->getResult();
    }

    public function cpostAction(Request $request, Meeting $meeting)
    {
        $protocol = new Protocol();
        $protocol->setMeeting($meeting);
        
        return $this->processForm($request, $protocol);
    }

    public function getAction(Meeting $meeting, Protocol $protocol)
    {
        return $protocol;
    }

    public function putAction(Request $request, Meeting $meeting, Protocol $protocol)
    {
        return $this->processForm($request, $protocol);
    }

    public function deleteAction(Meeting $meeting, Protocol $protocol)
    {
        $this->get("omma.app.manager.protocol")->delete($protocol);
        
        return $this->view("");
    }

    protected function processForm(Request $request, Protocol $protocol)
    {
        $new = null === $protocol->getId();
        $form = $this->createForm(new MeetingProtocolForm(), $protocol, array(
            "method" => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->get("omma.app.manager.protocol")->save($protocol);
            
            return $this->view($protocol);
        }
        
        return $this->view($form, 400);
    }
}
