<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationStorage
{
    private const KEY = '_app/org';

    /** @var SessionInterface */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function save(Organization $organization): void
    {
        $this->session->set(self::KEY, $organization);
    }

    public function getOrCreateNew(): Organization
    {
        $organization = null;

        if ($this->session->has(self::KEY)) {
            $stored = $this->session->get(self::KEY);
            if ($stored instanceof Organization) {
                $organization = $stored;
            }
        }

        if (!$organization instanceof Organization) {
            $organization = new Organization();
        }

        return $organization;
    }
}
