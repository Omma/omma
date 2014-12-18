<?php
namespace Omma\AppBundle\Twig;

use Omma\AppBundle\Entity\Task;
use Omma\AppBundle\Entity\TaskEntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class TaskTwigExtension extends \Twig_Extension
{
    /**
     * @var TaskEntityManager
     */
    protected $taskEntityManager;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(TaskEntityManager $taskEntityManager, TokenStorageInterface $tokenStorage)
    {
        $this->taskEntityManager = $taskEntityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions()
    {
        return array(
            "task_get_open" => new \Twig_SimpleFunction("task_get_open", array($this, "getOpenTasks"))
        );
    }

    public function getOpenTasks()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (null === $user) {
            return array();
        }

        return $this->taskEntityManager->createQueryBuilder("t")
            ->select("t")
            ->where("t.user = :user AND t.status = :status")
            ->setParameter("user", $user)
            ->setParameter("status", Task::STATUS_OPEN)
            ->orderBy("t.priority", "DESC")
            ->orderBy("t.date", "ASC")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getName()
    {
        return "omma_task";
    }
}
