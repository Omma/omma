<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Protocol
 *
 * @ORM\Table("omma_protocol")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Adrian Woeltche
 */
class Protocol extends Base
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
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="protocol")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\OneToOne(targetEntity="File", inversedBy="protocol")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     *
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(name="text", type="text")
     *
     * @var string
     */
    private $text;

    /**
     * @ORM\Column(name="final", type="boolean")
     *
     * @var boolean
     */
    private $final;

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
     * Set text
     *
     * @param string $text
     * @return Protocol
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set final
     *
     * @param boolean $final
     * @return Protocol
     */
    public function setFinal($final)
    {
        $this->final = $final;

        return $this;
    }

    /**
     * Get final
     *
     * @return boolean
     */
    public function getFinal()
    {
        return $this->final;
    }

    /**
     * Set meeting
     *
     * @param \Omma\AppBundle\Entity\Meeting $meeting
     * @return Protocol
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
     * Set file
     *
     * @param \Omma\AppBundle\Entity\File $file
     * @return Protocol
     */
    public function setFile(\Omma\AppBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Omma\AppBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }
}
