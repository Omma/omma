<?php
namespace Omma\AppBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class AbstractEntityManager
{
    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    public function create()
    {
        return new $this->class();
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository($this->class);
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function refresh($entity)
    {
        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException("entity must be instance of " . $this->class);
        }

        $this->em->refresh($entity);
    }

    public function merge($entity)
    {
        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException("entity must be instance of " . $this->class);
        }

        return $this->em->merge($entity);
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

    public function save($entity, $flush = true)
    {
        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException("entity must be instance of " . $this->class);
        }
        $this->em->persist($entity);
        if ($flush) {
            $this->em->flush();
        }
    }

    public function delete($entity, $flush = true)
    {
        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException("entity must be instance of " . $this->class);
        }
        $this->em->remove($entity);
        if ($flush) {
            $this->em->flush();
        }
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->getRepository()->findOneBy($criteria, $orderBy);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findAll()
    {
        return $this->getRepository()->findAll();
    }
}
