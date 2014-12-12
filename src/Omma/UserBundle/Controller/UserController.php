<?php

namespace Omma\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController implements ClassResourceInterface
{
    public function cgetAction(Request $request)
    {
        $search = $request->query->get("search");
        $query = $this->get("omma.user.orm.user_manager")->createQueryBuilder("u")
            ->select("u")
        ;
        if (!empty($search)) {
            $query
                ->where("u.username LIKE :search")
                ->orWhere("u.email LIKE :search")
                ->orWhere("u.firstname LIKE :search")
                ->orWhere("u.lastname LIKE :search")
                ->setParameter("search", "%" . $search . "%")
            ;
        }
        $users = $query
            ->orderBy("u.username")
            ->setFirstResult(0)
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
        ;

        return $users;
    }
}
