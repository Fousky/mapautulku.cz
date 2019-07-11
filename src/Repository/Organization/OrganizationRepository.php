<?php declare(strict_types = 1);

namespace App\Repository\Organization;

use App\Entity\Geo\District;
use App\Entity\Geo\DistrictZipCode;
use App\Entity\Geo\Municipality;
use App\Entity\Geo\Region;
use App\Entity\Organization\Organization;
use App\Model\OrganizationList\OrganizationFilter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class OrganizationRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Organization::class));
    }

    public function createFilteredPaginator(
        OrganizationFilter $filters
    ): Pagerfanta
    {
        $builder = $this->createPublicQueryBuilder('organization');

        $filters->apply($builder);

        $paginator = new Pagerfanta(new DoctrineORMAdapter($builder->getQuery()));
        $paginator->setMaxPerPage($filters->getPerPage());
        $paginator->setCurrentPage($filters->getPage());

        return $paginator;
    }

    public function createPublicQueryBuilder(string $alias): QueryBuilder
    {
        return $this
            ->createQueryBuilder($alias)
            ->where(sprintf('%s.public = 1', $alias));
    }

    public function save(Organization $organization): void
    {
        $this->_em->persist($organization);
        $this->_em->flush($organization);
    }

    public function mergeLocalities(Organization $organization): void
    {
        $this->mergeRegion($organization);
        $this->mergeDistrict($organization);
        $this->mergeMunicipality($organization);
        $this->mergeZipCode($organization);
    }

    private function mergeRegion(Organization $organization): void
    {
        $region = $organization->getRegion();

        if ($region) {
            $merged = $this->_em->merge($region);
            $organization->setRegion($merged instanceof Region ? $merged : null);
        }
    }

    private function mergeDistrict(Organization $organization): void
    {
        $district = $organization->getDistrict();

        if ($district) {
            $merged = $this->_em->merge($district);
            $organization->setDistrict($merged instanceof District ? $merged : null);
        }
    }

    private function mergeMunicipality(Organization $organization): void
    {
        $municipality = $organization->getMunicipality();

        if ($municipality) {
            $merged = $this->_em->merge($municipality);
            $organization->setMunicipality($merged instanceof Municipality ? $merged : null);
        }
    }

    private function mergeZipCode(Organization $organization): void
    {
        $zipCode = $organization->getZip();

        if ($zipCode) {
            $merged = $this->_em->merge($zipCode);
            $organization->setZip($merged instanceof DistrictZipCode ? $merged : null);
        }
    }
}
