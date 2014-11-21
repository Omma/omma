<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Agenda;
use Omma\AppBundle\Entity\Meeting;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingAgendaController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(Meeting $meeting)
    {

    }

    public function cpostAction(Meeting $meeting)
    {

    }

    public function getAction(Meeting $meeting, Agenda $agenda)
    {

    }
}
