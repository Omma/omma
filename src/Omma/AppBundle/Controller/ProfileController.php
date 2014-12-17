<?php
namespace Omma\AppBundle\Controller;

use Application\Sonata\UserBundle\Entity\User;
use Omma\AppBundle\Form\Type\ProfileForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="omma_app_profile")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $builder = $this->get("form.factory")->createBuilder(new ProfileForm(), $user);
        if (null !== $user->getLdapId()) {
            $builder->setDisabled(true);
        }
        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get("fos_user.user_manager")->updateUser($user);

            return $this->redirect($this->generateUrl("omma_app_profile"));
        }
        return array(
            "user" => $user,
            "form" => $form->createView(),
        );
    }
}
