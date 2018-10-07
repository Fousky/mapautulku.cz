<?php declare(strict_types = 1);

namespace App\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Okresy ČR
 *
 * @ORM\Entity(repositoryClass="App\Repository\Geo\DistrictRepository")
 * @ORM\Table(
 *     name="geo_district",
 *     indexes={
 *         @ORM\Index(columns={"title"}),
 *     },
 * )
 */
class District
{
    /**
     * @var UuidInterface
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="district_id", type="uuid")
     */
    protected $id;

    /**
     * @var Region|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\Region", inversedBy="districts", fetch="EAGER")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="region_id")
     */
    protected $region;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString()
    {
        $region = $this->getRegion();

        if ($region === null) {
            return (string) $this->getTitle();
        }

        return sprintf('%s (%s)', (string) $this->getTitle(), (string) $region->getTitle());
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
