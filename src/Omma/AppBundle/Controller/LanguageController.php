<?php
namespace Omma\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for switching the current user language
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LanguageController extends Controller
{
    /**
     * @Route("/switch_language/{language}", name="omma_switch_language")
     */
    public function switchAction(Request $request, $language)
    {
        $languages = $this->container->getParameter("omma.languages");
        if (!in_array($language, $languages)) {
            throw new NotFoundHttpException("Language not found");
        }

        $request->getSession()->set("_locale", $language);
        $referer = $request->headers->get("referer");

        if (null === $referer) {
            return $this->redirect($this->generateUrl("omma_index"));
        }

        return $this->redirect($referer);
    }
}
