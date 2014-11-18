<?php
namespace Omma\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Displays login form
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LoginController extends Controller
{
    /**
     * @Route("/login", name="omma_login")
     * @Template()
     *
     * @return array
     */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        $csrfToken = $this->container->get("form.csrf_provider")->generateCsrfToken("authenticate");

        return array(
            "last_username" => $this->get("request")->getSession()->get(SecurityContext::LAST_USERNAME),
            "error"         => $error,
            "csrf_token"    => $csrfToken,
        );
    }

    /**
     * @Route("/login_check", name="omma_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="omma_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}
