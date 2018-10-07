<?php declare(strict_types = 1);

namespace App\Model\Mailer;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Recipients
{
    /** @var string[] */
    protected $organizationAdd;

    public function __construct(array $organizationAdd)
    {
        $this->organizationAdd = $organizationAdd;
    }

    /**
     * @return string[]
     */
    public function organizationAdd(): array
    {
        return $this->organizationAdd;
    }
}
