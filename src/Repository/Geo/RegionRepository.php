<?php declare(strict_types = 1);

namespace App\Repository\Geo;

use App\Entity\Geo\Region;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class RegionRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Region::class));
    }

    public function findByTitle(string $title): ?Region
    {
        $region = $this->findOneBy([
            'title' => $title,
        ]);

        if ($region === null || $region instanceof Region) {
            return $region;
        }

        return null;
    }

    public function save(Region $region): void
    {
        $this->_em->persist($region);
        $this->_em->flush($region);
    }

    public function createChoices(): array
    {
        /** @var Region[] $regions */
        $regions = $this->findBy([], ['title' => 'ASC']);
        $result = [];

        $result['Všechny kraje'] = '';

        foreach ($regions as $region) {
            $result[$region->getTitle()] = $region->getId()->toString();
        }

        return $result;
    }
}
