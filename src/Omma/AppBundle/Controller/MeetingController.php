<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Form\Type\MeetingForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction()
    {
        return $this->get("omma.app.manager.meeting")->findAll();
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\Form\Form
     */
    public function cpostAction(Request $request)
    {
        $form = $this->createForm(new MeetingForm(), null, array(
            "method" => "POST",
            "csrf_protection" => false,
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->view($form->getData());
        }

        return $this->view($form, 400);
    }

    public function getAction(Meeting $meeting)
    {
        return $meeting;
    }
}
