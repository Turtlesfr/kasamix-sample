<?php

namespace KasamixBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(
 *     fields={"email"},
 *     message="user.error.form.email"
 *     )
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="KasamixBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    const USER_GENDER_MISS = 'profile.show.gender.miss';
    const USER_GENDER_MADAM = 'profile.show.gender.madam';
    const USER_GENDER_MISTER = 'profile.show.gender.mister';

    const USER_ROLES = [
        'profile.roles.ROLE_USER'        => 'ROLE_USER',
        'profile.roles.ROLE_CLIENT'      => 'ROLE_CLIENT',
        'profile.roles.ROLE_PROMOTOR'    => 'ROLE_PROMOTOR',
        'profile.roles.ROLE_COMMERCIAL'  => 'ROLE_COMMERCIAL',
        'profile.roles.ROLE_ADVERTISER'  => 'ROLE_ADVERTISER',
        'profile.roles.ROLE_ADMIN'       => 'ROLE_ADMIN',
        'profile.roles.ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="profile.error.email.blank")
     * @Assert\Email(message="profile.error.email.format")
     */
    protected $email;

    /**
     * @Assert\NotBlank(message="profile.error.password.error", groups={"create"})
     * @Assert\Length(min=8, max="20", minMessage="profile.error.password.minLength")
     * @Assert\Regex(
     *     pattern="/^(?:(?=.*[a-z])(?:(?=.*[A-Z])(?=.*[\d\W])|(?=.*\W)(?=.*\d))|(?=.*\W)(?=.*[A-Z])(?=.*\d)).{8,}$/",
     *     match=true,
     *     message="profile.error.password.requirements"
     * )
     */
    protected $plainPassword;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", columnDefinition="enum('miss', 'madam', 'mister')")
     */
    private $gender;
    /**
     * @var string
     * @Assert\NotBlank(message="profile.error.firstName")
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;
    /**
     * @var string
     * @Assert\NotBlank(message="profile.error.lastName")
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;
    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=255, nullable=true)
     */
    private $address1;
    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;
    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=8, nullable=true)
     */
    private $zip;
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;
    /**
     * @var string
     * @Assert\Country(message="profile.error.country")
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;
    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;
    /**
     * @var string
     *
     * @ORM\Column(name="cellPhone", type="string", length=255, nullable=true)
     */
    private $cellPhone;

    /**
     * @var string
     * @ORM\Column(name="job", type="string", length=255, nullable=true)
     */
    private $job;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="KasamixBundle\Entity\Company", inversedBy="user")
     * @ORM\JoinTable(name="user_to_company",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     * )
     */
    private $company;

    /**
     * One User has Many children's.
     * @ORM\OneToMany(targetEntity="KasamixBundle\Entity\User", mappedBy="parent")
     */
    private $children;

    /**
     * Many children's have One User.
     * @ORM\ManyToOne(targetEntity="KasamixBundle\Entity\User", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="KasamixBundle\Entity\Subscription", mappedBy="user")
     * @var PersistentCollection
     */
    private $hasProject;

    /**
     * @ORM\ManyToOne(targetEntity="KasamixBundle\Entity\Accommodation", inversedBy="client")
     */
    private $acommodation;

    /**
     * @ORM\Column(name="has_approved_accommodation", type="smallint", nullable=false, options={"default" : 0})
     */
    private $hasApprovedAccommodation;

    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function __construct()
    {
        parent::__construct();
        $this->children = new ArrayCollection();
        $this->hasProject = new ArrayCollection();
    }

    /**
     * Get id
     * @Groups({"public"})
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get gender
     * @Groups({"public"})
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get firstName
     * @Groups({"public"})
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName
     * @Groups({"public"})
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get address1
     * @Groups({"public"})
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return User
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address2
     * @Groups({"public"})
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return User
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get zip
     * @Groups({"public"})
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return User
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get city
     * @Groups({"public"})
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get country
     * @Groups({"public"})
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get phone
     * @Groups({"public"})
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get cellPhone
     * @Groups({"public"})
     * @return string
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Set cellPhone
     *
     * @param string $cellPhone
     *
     * @return User
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;

        return $this;
    }

    /**
     * Get job
     * @Groups({"public"})
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set job
     *
     * @param string $job
     *
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get comment
     * @Groups({"public"})
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return User
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @Groups({"public"})
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param $company
     *
     * @return User
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @Groups({"public"})
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     *
     * @return User
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @Groups({"public"})
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     *
     * @return User
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcommodation()
    {
        return $this->acommodation;
    }

    /**
     * @param mixed $acommodation
     *
     * @return User
     */
    public function setAcommodation($acommodation)
    {
        $this->acommodation = $acommodation;

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getHasProject(): PersistentCollection
    {
        return $this->hasProject;
    }

    /**
     *
     */
    public function setHasProject(array $hasProject)
    {
        $this->hasProject[] = $hasProject;
    }

    public function getHasApprovedAccommodation()
    {
        return $this->hasApprovedAccommodation;
    }

    /**
     * @param mixed $showSlaveRoomsOnDashboard
     *
     * @return Accommodation
     */
    public function setHasApprovedAccommodation($hasApprovedAccommodation)
    {
        $this->hasApprovedAccommodation = $hasApprovedAccommodation;

        return $this;
    }
}
