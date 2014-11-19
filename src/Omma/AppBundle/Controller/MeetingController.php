<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;

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

    public function getAction(Meeting $meeting)
    {
        return $meeting;
    }
}
