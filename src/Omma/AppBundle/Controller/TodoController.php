<?php
namespace Omma\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *
 *
 * @author Johannes Höhn <johannes.hoehn@hof-university.de>
 */
class TodoController extends Controller
{
    /**
     * @Route("/todos")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
