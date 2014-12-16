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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="files")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * @ORM\OneToOne(targetEntity="Protocol", mappedBy="file")
     *
     * @var Protocol
     */
    protected $protocol;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="parent")
     *
     * @var ArrayCollection
     */
    protected $subFiles;

    /**
     * @ORM\ManyToOne(targetEntity="File", inversedBy="subFiles")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     *
     * @var File
     */
    protected $parent;

    /**
     * @ORM\Column(name="type", type="boolean")
     *
     * @var boolean
     */
    protected $type;

    /**
     * @ORM\Column(name="url", type="text")
     *
     * @var string
     */
    protected $url;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subFiles = new ArrayCollection();
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
     * @param Meeting $meeting
     *
     * @return File
     */
    public function setMeeting(Meeting $meeting)
    {
        if ($this->meeting !== $meeting) {
            $this->meeting = $meeting;
            if (null !== $meeting) {
                $meeting->addFile($this);
            }
        }

        return $this;
    }

    /**
     * Get meeting
     *
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set protocol
     *
     * @param Protocol $protocol
     *
     * @return File
     */
    public function setProtocol(Protocol $protocol = null)
    {
        if ($this->protocol !== $protocol) {
            $this->protocol = $protocol;
            if (null !== $protocol) {
                $protocol->setFile($this);
            }
        }

        return $this;
    }

    /**
     * Get protocol
     *
     * @return Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Add subFiles
     *
     * @param File $subFile
     *
     * @return File
     */
    public function addSubFile(File $subFile)
    {
        if (!$this->subFiles->contains($subFile)) {
            $this->subFiles->add($subFile);
            $subFile->setParent($this);
        }

        return $this;
    }

    /**
     * Remove subFiles
     *
     * @param File $subFile
     */
    public function removeSubFile(File $subFile)
    {
        if ($this->subFiles->removeElement($subFile)) {
            $subFile->setParent(null);
        }
    }

    /**
     * Get subFiles
     *
     * @return File[]
     */
    public function getSubFiles()
    {
        return $this->subFiles;
    }

    /**
     * Set parent
     *
     * @param File $parent
     *
     * @return File
     */
    public function setParent(File $parent = null)
    {
        if ($this->parent !== $parent) {
            $this->parent = $parent;
            if (null !== $parent) {
                $parent->addSubFile($this);
            }
        }

        return $this;
    }

    /**
     * Get parent
     *
     * @return File
     */
    public function getParent()
    {
        return $this->parent;
    }
}
