<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * File
 *
 * @ORM\Table("omma_file")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class File extends Base
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="files")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\OneToOne(targetEntity="Protocol", mappedBy="file")
     *
     * @var Protocol
     */
    private $protocol;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="parent")
     *
     * @var ArrayCollection
     */
    private $subFiles;

    /**
     * @ORM\ManyToOne(targetEntity="File", inversedBy="subFiles")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     *
     * @var File
     */
    private $parent;

    /**
     * @ORM\Column(name="type", type="boolean")
     *
     * @var boolean
     */
    private $type;

    /**
     * @ORM\Column(name="url", type="text")
     *
     * @var string
     */
    private $url;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subFiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param boolean $type
     * @return File
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return File
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set meeting
     *
     * @param \Omma\AppBundle\Entity\Meeting $meeting
     * @return File
     */
    public function setMeeting(\Omma\AppBundle\Entity\Meeting $meeting)
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * Get meeting
     *
     * @return \Omma\AppBundle\Entity\Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set protocol
     *
     * @param \Omma\AppBundle\Entity\Protocol $protocol
     * @return File
     */
    public function setProtocol(\Omma\AppBundle\Entity\Protocol $protocol = null)
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * Get protocol
     *
     * @return \Omma\AppBundle\Entity\Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Add subFiles
     *
     * @param \Omma\AppBundle\Entity\File $subFiles
     * @return File
     */
    public function addSubFile(\Omma\AppBundle\Entity\File $subFiles)
    {
        $this->subFiles[] = $subFiles;

        return $this;
    }

    /**
     * Remove subFiles
     *
     * @param \Omma\AppBundle\Entity\File $subFiles
     */
    public function removeSubFile(\Omma\AppBundle\Entity\File $subFiles)
    {
        $this->subFiles->removeElement($subFiles);
    }

    /**
     * Get subFiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubFiles()
    {
        return $this->subFiles;
    }

    /**
     * Set parent
     *
     * @param \Omma\AppBundle\Entity\File $parent
     * @return File
     */
    public function setParent(\Omma\AppBundle\Entity\File $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Omma\AppBundle\Entity\File
     */
    public function getParent()
    {
        return $this->parent;
    }
}
