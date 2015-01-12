<?php

namespace Omma\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller, which redirects to the dashboard for logged in users.
 * Should display general information about the app for guests.
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="omma_index")
     * @Template()
     */
    public function indexAction()
    {
        $token = $this->get("security.context")->getToken();
        if ($token->getUser()) {
            return $this->redirect($this->generateUrl("omma_dashboard"));
        }


    }
}
