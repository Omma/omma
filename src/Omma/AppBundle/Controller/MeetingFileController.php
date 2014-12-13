<?php
namespace Omma\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Omma\AppBundle\Entity\Meeting;
use Omma\AppBundle\Entity\Protocol;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Omma\AppBundle\Entity\Task;
use Omma\AppBundle\Entity\File;
use Omma\AppBundle\Form\Type\MeetingFileForm;

/**
 *
 * @RouteResource("File")
 *
 * @author Adrian Woeltche
 */
class MeetingFileController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     */
    public function cgetAction(Meeting $meeting)
    {
        return $this->get("omma.app.manager.file")
            ->createQueryBuilder("f")
            ->select("f")
            ->where("f.meeting = :meeting")
            ->setParameter("meeting", $meeting)
            ->getQuery()
            ->getResult();
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cpostAction(Request $request, Meeting $meeting)
    {
        $file = new File();
        $file->setMeeting($meeting);

        return $this->processForm($request, $file);
    }

    /**
     * @Security("is_granted('view', meeting)")
     *
     * @param Meeting $meeting
     * @param File $file
     *
     * @return Protocol
     */
    public function getAction(Meeting $meeting, File $file)
    {
        return $file;
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Request $request
     * @param Meeting $meeting
     * @param File $file
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, Meeting $meeting, File $file)
    {
        return $this->processForm($request, $file);
    }

    /**
     * @Security("is_granted('edit', meeting)")
     *
     * @param Meeting $meeting
     * @param File $file
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Meeting $meeting, File $file)
    {
        $this->get("omma.app.manager.file")->delete($file);

        return $this->view("");
    }

    protected function processForm(Request $request, File $file)
    {
        $new = null === $file->getId();
        $form = $this->createForm(new MeetingFileForm(), $file, array(
            "method" => $new ? "POST" : "PUT",
            "csrf_protection" => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get("omma.app.manager.file")->save($file);

            return $this->view($file);
        }

        return $this->view($form, 400);
    }
}
