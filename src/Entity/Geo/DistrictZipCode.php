<?php declare(strict_types = 1);

namespace App\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Geo\DistrictZipCodeRepository")
 * @ORM\Table(
 *     name="geo_district_zip",
 *     indexes={
 *         @Index(columns={"zip_code"}),
 *         @Index(columns={"city"}),
 *         @Index(columns={"city_part"}),
 *     },
 * )
 */
class DistrictZipCode
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="district_zip_id", type="uuid")
     */
    protected $id;

    /**
     * @var District|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\District")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="district_id", onDelete="CASCADE")
     */
    protected $district;

    /**
     * @var Municipality|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\Municipality")
     * @ORM\JoinColumn(name="municipality_id", referencedColumnName="municipality_id", onDelete="CASCADE")
     */
    protected $municipality;

    /**
     * @var int|null
     * @ORM\Column(name="zip_code", type="integer", length=5, nullable=false)
     */
    protected $zipCode;

    /**
     * @var string|null
     * @ORM\Column(name="city", type="string", length=100, nullable=false)
     */
    protected $city;

    /**
     * @var string|null
     * @ORM\Column(name="city_part", type="string", length=100, nullable=false)
     */
    protected $cityPart;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString()
    {
        return (string) $this->getZipCode();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(?int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCityPart(): ?string
    {
        return $this->cityPart;
    }

    public function setCityPart(?string $cityPart): self
    {
        $this->cityPart = $cityPart;

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
}
