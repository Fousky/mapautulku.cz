<?php declare(strict_types = 1);

namespace App\Entity\Organization;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organization_has_category")
 */
class OrganizationHasCategory
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="uuid")
     */
    protected $id;

    /**
     * @var Organization|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization\Organization", inversedBy="hasCategories")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="organization_id", onDelete="CASCADE")
     */
    protected $organization;

    /**
     * @var Category|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization\Category", inversedBy="hasOrganizations")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id", onDelete="CASCADE")
     */
    protected $category;

    /**
     * @var int|null
     * @ORM\Column(name="position", type="integer", nullable=true)
     *
     * @Gedmo\SortablePosition()
     */
    protected $position;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString()
    {
        return (string) $this->getCategory();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
