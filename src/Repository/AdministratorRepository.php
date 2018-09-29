<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Administrator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class AdministratorRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Administrator::class));
    }

    public function findByEmail(string $email): ?Administrator
    {
        $admin =  $this->findOneBy([
            'email' => $email,
        ]);

        if ($admin instanceof Administrator || $admin === null) {
            return $admin;
        }

        throw new \LogicException('AdministratorRepository::findByEmail unreachable.');
    }
}
