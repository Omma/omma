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
     * @ORM\ManyToOne(targetEntity="Meeting", inversedBy="files")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", nullable=false)
     */
    protected $meeting;

    /**
     * @var Protocol
     *
     * @ORM\OneToOne(targetEntity="Protocol", mappedBy="file")
     */
    protected $protocol;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="parent")
     */
    protected $subFiles;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="File", inversedBy="subFiles")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    protected $url;
}
