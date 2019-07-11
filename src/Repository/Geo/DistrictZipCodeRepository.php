<?php declare(strict_types = 1);

namespace App\Repository\Geo;

use App\Entity\Geo\DistrictZipCode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DistrictZipCodeRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(DistrictZipCode::class));
    }

    public function findByZipAndCityPart(string $zip, string $cityPart): ?DistrictZipCode
    {
        return $this->createQueryBuilder('zip')
            ->where('zip.zipCode LIKE :zip')
            ->andWhere('zip.cityPart LIKE :cityPart')
            ->setParameter('zip', $zip . '%')
            ->setParameter('cityPart', $cityPart)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
