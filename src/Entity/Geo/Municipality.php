<?php declare(strict_types = 1);

namespace App\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Obce ČR
 *
 * @ORM\Entity(repositoryClass="App\Repository\Geo\MunicipalityRepository")
 * @ORM\Table(name="geo_municipality")
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Municipality
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="municipality_id", type="uuid")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    /**
     * @var District|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\District")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="district_id", onDelete="CASCADE")
     */
    protected $district;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString()
    {
        $district = $this->getDistrict();

        if ($district === null) {
            return (string) $this->getTitle();
        }

        $region = $district->getRegion();

        if ($region === null) {
            return sprintf('%s (okres %s)', $this->getTitle(), $district->getTitle());
        }

        return sprintf(
            '%s (okres %s, %s)',
            $this->getTitle(),
            $district->getTitle(),
            $region->getTitle()
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
}
