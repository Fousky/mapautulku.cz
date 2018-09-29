<?php declare(strict_types = 1);

namespace App\Repository\Geo;

use App\Entity\Geo\Municipality;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class MunicipalityRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Municipality::class));
    }

    public function save(Municipality $municipality): void
    {
        $this->_em->persist($municipality);
        $this->_em->flush($municipality);
    }
}
