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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Meeting
     *
     * @ORM\OneToOne(targetEntity="Meeting", inversedBy="protocol")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     */
    protected $meeting;

    /**
     * @var File
     *
     * @ORM\OneToOne(targetEntity="File", inversedBy="protocol")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="final", type="boolean")
     */
    protected $final;
}
