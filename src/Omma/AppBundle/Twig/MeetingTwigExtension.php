<?php
namespace Omma\AppBundle\Twig;

use Omma\AppBundle\Entity\MeetingEntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Twig extension for displying upcomming meeting in the sidebar
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class MeetingTwigExtension extends \Twig_Extension
{

    /**
     * @var MeetingEntityManager
     */
    protected $meetingEntityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(MeetingEntityManager $meetingEntityManager, TokenStorageInterface $tokenStorage)
    {
        $this->meetingEntityManager = $meetingEntityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions()
    {
        return array(
            "meeting_get_upcomming" => new \Twig_SimpleFunction("meeting_get_upcomming", array($this, "getUpcommingMeetings"))
        );
    }

    /**
     * @return \Omma\AppBundle\Entity\Meeting[]
     */
    public function getUpcommingMeetings()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (null === $user) {
            return array();
        }

        return $this->meetingEntityManager->getUpcommingForUser($user);
    }

    public function getName()
    {
        return "omma_meeting";
    }
}
