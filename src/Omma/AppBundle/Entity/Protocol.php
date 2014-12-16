<?php
namespace Omma\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="protocol")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * @ORM\OneToOne(targetEntity="File", inversedBy="protocol")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     *
     * @var File
     */
    protected $file;

    /**
     * @ORM\Column(name="text", type="text")
     * @NotBlank()
     *
     * @var string
     */
    protected $text;

    /**
     * @ORM\Column(name="final", type="boolean", nullable=true)
     *
     * @var boolean
     */
    protected $final;

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
     *
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
     *
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
    public function isFinal()
    {
        return $this->final;
    }

    /**
     * Set meeting
     *
     * @param Meeting $meeting
     *
     * @return Protocol
     */
    public function setMeeting(Meeting $meeting = null)
    {
        if ($this->meeting !== $meeting) {
            $this->meeting = $meeting;
            if (null !== $meeting) {
                $meeting->setProtocol($this);
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
     * Set file
     *
     * @param File $file
     *
     * @return Protocol
     */
    public function setFile(File $file = null)
    {
        if ($this->file !== $file) {
            $this->file = $file;
            if (null !== $file) {
                $file->setProtocol($this);
            }
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
