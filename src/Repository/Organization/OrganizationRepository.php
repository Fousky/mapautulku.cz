<?php declare(strict_types = 1);

namespace App\Repository\Organization;

use App\Entity\Organization\Organization;
use App\Model\OrganizationList\OrganizationFilter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
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
        $builder = $this->createQueryBuilder('organization');

        $filters->apply($builder);

        $paginator = new Pagerfanta(new DoctrineORMAdapter($builder->getQuery()));
        $paginator->setMaxPerPage($filters->getPerPage());
        $paginator->setCurrentPage($filters->getPage());

        return $paginator;
    }
}
