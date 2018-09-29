<?php declare(strict_types = 1);

namespace App\Entity\Organization;

use App\Entity\Geo\District;
use App\Entity\Geo\Municipality;
use App\Entity\Geo\Region;
use App\Model\Doctrine\Point;
use App\Model\DoctrineTraits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organization")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Organization
{
    use TimestampableTrait;

    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="organization_id", type="uuid")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"name"}, updatable=true, unique=true)
     */
    protected $slug;

    /**
     * CRN (=company registration number = IČ)
     *
     * @var string|null
     * @ORM\Column(name="crn", type="string", length=12, nullable=true)
     */
    protected $crn;

    /**
     * TIN (=tax identification number = DIČ)
     *
     * @var string|null
     * @ORM\Column(name="tin", type="string", length=12, nullable=true)
     */
    protected $tin;

    /**
     * @var string|null
     * @ORM\Column(name="www", type="string", nullable=true)
     */
    protected $www;

    /**
     * @var string|null
     * @ORM\Column(name="facebook", type="string", nullable=true)
     */
    protected $facebook;

    /**
     * @var string|null
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string|null
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var string|null
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    protected $address;

    /**
     * @var Point|null
     * @ORM\Column(name="gps", type="point", nullable=true)
     */
    protected $gps;

    /**
     * @var Region|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="region_id", onDelete="SET NULL")
     */
    protected $region;

    /**
     * @var District|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\District")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="district_id", onDelete="SET NULL")
     */
    protected $district;

    /**
     * @var Municipality|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\Municipality")
     * @ORM\JoinColumn(name="municipality_id", referencedColumnName="municipality_id", onDelete="SET NULL")
     */
    protected $municipality;

    /**
     * @var OrganizationHasCategory[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Organization\OrganizationHasCategory",
     *     mappedBy="organization",
     *     cascade={"persist"},
     *     orphanRemoval=true,
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $hasCategories;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->hasCategories = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCrn(): ?string
    {
        return $this->crn;
    }

    public function setCrn(?string $crn): self
    {
        $this->crn = $crn;

        return $this;
    }

    public function getTin(): ?string
    {
        return $this->tin;
    }

    public function setTin(?string $tin): self
    {
        $this->tin = $tin;

        return $this;
    }

    public function getWww(): ?string
    {
        return $this->www;
    }

    public function setWww(?string $www): self
    {
        $this->www = $www;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getGps(): ?Point
    {
        return $this->gps;
    }

    public function setGps(?Point $gps): self
    {
        $this->gps = $gps;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getMunicipality(): ?Municipality
    {
        return $this->municipality;
    }

    public function setMunicipality(?Municipality $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * @return OrganizationHasCategory[]|ArrayCollection
     */
    public function getHasCategories()
    {
        return $this->hasCategories;
    }

    public function addHasCategory(OrganizationHasCategory $hasCategory): self
    {
        if (!$this->hasCategories->contains($hasCategory)) {
            $this->hasCategories->add($hasCategory);
            $hasCategory->setOrganization($this);
        }

        return $this;
    }

    public function removeHasCategory(OrganizationHasCategory $hasCategory): self
    {
        if ($this->hasCategories->contains($hasCategory)) {
            $this->hasCategories->removeElement($hasCategory);
        }

        return $this;
    }

    /**
     * @param OrganizationHasCategory[]|ArrayCollection $hasCategories
     *
     * @return $this
     */
    public function setHasCategories($hasCategories): self
    {
        $this->hasCategories = $hasCategories;

        foreach ($hasCategories as $hasCategory) {
            $hasCategory->setOrganization($this);
        }

        return $this;
    }
}
