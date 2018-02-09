<?php

namespace KasamixBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Accommodation
 *
 * @ORM\Table(name="accommodation")
 * @ORM\Entity(repositoryClass="KasamixBundle\Repository\AccommodationRepository")
 */
class Accommodation
{
    const ACCOMMODATION_STATUS_NONVALIDATED = 1;
    const ACCOMMODATION_STATUS_VALIDATED = 2;
    const ACCOMMODATION_STATUS_MAINTENANCE = 3;
    const ACCOMMODATION_STATUS_BUILT = 4;

    const ACCOMMODATION_STATUSES = [
        self::ACCOMMODATION_STATUS_NONVALIDATED     => 'accommodation.status.nonvalidated',
        self::ACCOMMODATION_STATUS_VALIDATED        => 'accommodation.status.validated',
        self::ACCOMMODATION_STATUS_MAINTENANCE      => 'accommodation.status.maintenance',
        self::ACCOMMODATION_STATUS_BUILT            => 'accommodation.status.built',
    ];

    use TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="building", type="string", length=255, nullable=false)
     */
    private $building;

    /**
     * @ORM\Column(name="alley", type="string", length=255, nullable=false)
     */
    private $alley;

    /**
     * @ORM\Column(name="floor", type="string", length=255, nullable=false)
     */
    private $floor;

    /**
     * @ORM\Column(name="number", type="string", length=255, nullable=false)
     */
    private $number;

    /**
     * @ORM\Column(name="total_surface", type="float", precision=2, nullable=false)
     */
    private $totalSurface;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(name="date_end", type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\ManyToOne(targetEntity="KasamixBundle\Entity\AccommodationType", inversedBy="accommodation")
     * @ORM\JoinColumn(name="accommodation_type_id", referencedColumnName="id")
     */
    private $accommodationType;

    /**
     * @ORM\ManyToOne(targetEntity="KasamixBundle\Entity\Project", inversedBy="accommodation")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="KasamixBundle\Entity\Company", inversedBy="accommodation")
     * @ORM\JoinColumn(name="fitter_id", nullable=true, unique=false)
     */
    private $fitter;

    /**
     * @ORM\OneToMany(targetEntity="KasamixBundle\Entity\User", mappedBy="acommodation")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="KasamixBundle\Entity\Room", mappedBy="accommodation", cascade={"remove"})
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="KasamixBundle\Entity\Media", mappedBy="accommodation", cascade={"persist", "remove"})
     */
    private $media;

    public function __construct()
    {
        $this->status = self::ACCOMMODATION_STATUS_NONVALIDATED;
        //$this->fitter = new ArrayCollection();
        $this->fitter = Null;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Accommodation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     *
     * @return Accommodation
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlley()
    {
        return $this->alley;
    }

    /**
     * @param mixed $alley
     *
     * @return Accommodation
     */
    public function setAlley($alley)
    {
        $this->alley = $alley;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param mixed $floor
     *
     * @return Accommodation
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     *
     * @return Accommodation
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalSurface()
    {
        return $this->totalSurface;
    }

    /**
     * @param mixed $totalSurface
     *
     * @return Accommodation
     */
    public function setTotalSurface($totalSurface)
    {
        $this->totalSurface = $totalSurface;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     *
     * @return Accommodation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return Accommodation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param mixed $dateEnd
     *
     * @return Accommodation
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getAccommodationType()
    {
        return $this->accommodationType;
    }

    /**
     * @param mixed $accommodationType
     *
     * @return Accommodation
     */
    public function setAccommodationType($accommodationType)
    {
        $this->accommodationType = $accommodationType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     *
     * @return Accommodation
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return PersistentCollection|ArrayCollection
     */
    public function getFitter()
    {
        return $this->fitter;
    }

    /**
     * @param mixed $fitter
     *
     * @return Accommodation
     */
    public function setFitter($fitter)
    {
        $this->fitter = $fitter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param User $client
     *
     * @return Accommodation
     */
    public function setClient(User $client)
    {
        $this->client[] = $client;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     *
     * @return Accommodation
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     *
     * @return Accommodation
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }
}