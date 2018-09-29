<?php declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Kraje ČR
 *
 * @ORM\Entity(repositoryClass="App\Repository\Geo\RegionRepository")
 * @ORM\Table(name="geo_region")
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Region
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="region_id", type="uuid")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    /**
     * @var District[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Geo\District",
     *     mappedBy="region",
     *     cascade={"persist"},
     *     orphanRemoval=true,
     * )
     */
    protected $districts;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->districts = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getTitle();
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

    /**
     * @return District[]|ArrayCollection
     */
    public function getDistricts()
    {
        return $this->districts;
    }

    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts->add($district);
            $district->setRegion($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): self
    {
        if ($this->districts->contains($district)) {
            $this->districts->removeElement($district);
        }

        return $this;
    }

    /**
     * @param District[]|ArrayCollection $districts
     *
     * @return $this
     */
    public function setDistricts($districts): self
    {
        $this->districts = $districts;

        foreach ($districts as $district) {
            $district->setRegion($this);
        }

        return $this;
    }
}
