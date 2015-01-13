<?php
namespace Omma\AppBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Base class for entity manager
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

    /**
     * gets Entity class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * sets entity class name
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * create new entity object
     *
     * @return mixed
     */
    public function create()
    {
        return new $this->class();
    }

    /**
     * Gets the doctrine entity repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository($this->class);
    }

    /**
     * gets the assigned entity manager
     *
     * @return ObjectManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @see Doctrine\Common\Persistence\ObjectManager::refresh
     * @param object $entity
     */
    public function refresh($entity)
    {
        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException("entity must be instance of " . $this->class);
        }

        $this->em->refresh($entity);
    }

    /**
     * @see Doctrine\Common\Persistence\ObjectManager::merge
     *
     * @param object $entity
     *
     * @return object
     */
    public function merge($entity)
    {
        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException("entity must be instance of " . $this->class);
        }

        return $this->em->merge($entity);
    }

    /**
     * @see Doctrine\ORM\EntityRepository::createQueryBuilder
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias)
    {
        return $this->getRepository()->createQueryBuilder($alias);
    }

    /**
     * persists an entity
     *
     * @see Doctrine\Common\Persistence\ObjectManager::persist
     * @param object $entity
     * @param bool   $flush flush after persisting
     */
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

    /**
     * delete an entity
     *
     * @see Doctrine\Common\Persistence\ObjectManager::remove
     * @param object  $entity
     * @param bool    $flush flush after persisting
     */
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

    /**
     * @see Doctrine\Common\Persistence\ObjectManager::flush
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * @see Doctrine\ORM\EntityRepository::find
     *
     * @param int $id
     *
     * @return null|object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @see Doctrine\ORM\EntityRepository::findOneBy
     *
     * @param array $criteria
     * @param array $orderBy
     *
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->getRepository()->findOneBy($criteria, $orderBy);
    }

    /**
     * @see Doctrine\ORM\EntityRepository::findBy
     *
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @see Doctrine\ORM\EntityRepository::findAll
     *
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }
}
