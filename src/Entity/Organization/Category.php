<?php declare(strict_types = 1);

namespace App\Entity\Organization;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Category\CategoryRepository")
 * @ORM\Table(name="category")
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Category
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="category_id", type="uuid")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(name="name_in_akuzativ", type="string", nullable=false)
     *
     * @Assert\NotBlank()
     */
    protected $nameInAkuzativ;

    /**
     * @var string|null
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"nameInAkuzativ"}, updatable=true, unique=true)
     */
    protected $slug;

    /**
     * @var bool
     * @ORM\Column(name="is_public", type="boolean", options={"default":"1"})
     */
    protected $public = true;

    /**
     * @var OrganizationHasCategory[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Organization\OrganizationHasCategory",
     *     mappedBy="category",
     *     cascade={"persist"},
     *     orphanRemoval=true,
     * )
     */
    protected $hasOrganizations;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->hasOrganizations = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
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

    public function getNameInAkuzativ(): ?string
    {
        return $this->nameInAkuzativ;
    }

    public function setNameInAkuzativ(?string $nameInAkuzativ): self
    {
        $this->nameInAkuzativ = $nameInAkuzativ;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return OrganizationHasCategory[]|ArrayCollection
     */
    public function getHasOrganizations()
    {
        return $this->hasOrganizations;
    }

    public function addHasOrganization(OrganizationHasCategory $hasCategory): self
    {
        if (!$this->hasOrganizations->contains($hasCategory)) {
            $this->hasOrganizations->add($hasCategory);
            $hasCategory->setCategory($this);
        }

        return $this;
    }

    public function removeHasOrganization(OrganizationHasCategory $hasCategory): self
    {
        if ($this->hasOrganizations->contains($hasCategory)) {
            $this->hasOrganizations->removeElement($hasCategory);
        }

        return $this;
    }

    /**
     * @param OrganizationHasCategory[]|ArrayCollection $hasOrganizations
     *
     * @return $this
     */
    public function setHasOrganizations($hasOrganizations): self
    {
        $this->hasOrganizations = $hasOrganizations;

        foreach ($hasOrganizations as $hasCategory) {
            $hasCategory->setCategory($this);
        }

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
}
