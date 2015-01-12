<?php
namespace Omma\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller for the dashboard.
 * Provides only the template for the calendar
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
        );
    }
}
