<?php declare(strict_types = 1);

namespace App\Model\DoctrineTraits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
trait DefaultEnabledTrait
{
    /**
     * @var bool
     * @ORM\Column(name="is_enabled", type="boolean", options={"default":"1"})
     */
    protected $enabled = true;

    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
