<?php declare(strict_types = 1);

namespace App\Repository\Geo;

use App\Entity\Geo\District;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class DistrictRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(District::class));
    }

    public function save(District $district): void
    {
        $this->_em->persist($district);
        $this->_em->flush($district);
    }
}
