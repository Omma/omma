<?php
namespace Omma\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="omma_dashboard")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            "meetings" => $this->get("omma.app.manager.meeting")->findAll(), // @TODO: remove
        );
    }
}
