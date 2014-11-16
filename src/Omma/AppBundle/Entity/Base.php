<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Base
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class Base
{
    const MATCH_VAR = '/@var\s(.*?)[$|\s|\n]/';

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var AnnotationReader
     */
    private $reader;

    private function initReader()
    {
        if (!isset($this->reader)) {
            $this->reader = new AnnotationReader();
        }
    }

    public function __construct()
    {
        $this->initReader();

        $reflectionObject = new \ReflectionObject($this);
        $properties = $reflectionObject->getProperties();

        foreach ($properties as $property) {
            $annotation = preg_match(self::MATCH_VAR, $property->getDocComment(), $match);
            if (isset($annotation) && $match[1] == 'ArrayCollection') {
                $this->__set($property->getName(), new ArrayCollection());
            }
        }
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') !== false) {
            return $this->__get(lcfirst(substr($name, 3)));
        } else if (strpos($name, 'set') !== false && count($arguments) == 1) {
            $this->__set(lcfirst(substr($name, 3)), $arguments[0]);
        }
    }

    public function __set($name, $value)
    {
        $this->initReader();

        if (property_exists($this, $name)) {
            $property = new \ReflectionProperty($this, $name);
            $property->setAccessible($name);
            $annotation = preg_match(self::MATCH_VAR, $property->getDocComment(), $match);

            if (isset($annotation) && $match[1] == '\DateTime') {
                $property->setValue($this, (!empty($value)) ? new \DateTime($value) : null);
            } else {
                $property->setValue($this, (!empty($value)) ? $value : null);
            }
        }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            $property = new \ReflectionProperty($this, $name);
            $property->setAccessible($name);
            return $property->getValue($this);
        }

        return null;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
    }

    public function toArray()
    {
        $this->initReader();

        $reflectionObject = new \ReflectionClass($this);
        $properties = $reflectionObject->getProperties();

        $arr = array();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $annotation = $this->reader->getPropertyAnnotation($property, 'Doctrine\ORM\Mapping\Column');

            if (isset($annotation)) {
                $arr[$property->getName()] = $property->getValue($this);
            }
        }

        return $arr;
    }
}
?>