<?php declare(strict_types = 1);

namespace App\Repository\Geo;

use App\Entity\Geo\DistrictZipCode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class DistrictZipCodeRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(DistrictZipCode::class));
    }

    /**
     * @param string $zip
     *
     * @return DistrictZipCode[]
     */
    public function findByZip(string $zip): array
    {
        return $this->createQueryBuilder('zip')
            ->where('zip.zipCode LIKE :zip')
            ->setParameter('zip', $zip . '%')
            ->getQuery()
            ->getResult();
    }
}
