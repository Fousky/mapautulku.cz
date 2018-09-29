<?php declare(strict_types = 1);

namespace App\Model\DoctrineTraits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
trait TimestampableTrait
{
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function timestampable__prePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function timestampable__preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
