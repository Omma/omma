<?php
namespace Omma\UserBundle\Entity;

use Doctrine\ORM\QueryBuilder;
use Sonata\UserBundle\Entity\GroupManager;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class GroupEntityManager extends GroupManager
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias)
    {
        return $this->getRepository()->createQueryBuilder($alias);
    }
}
