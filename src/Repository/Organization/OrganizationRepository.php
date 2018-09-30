<?php declare(strict_types = 1);

namespace App\Repository\Organization;

use App\Entity\Organization\Organization;
use App\Model\OrganizationList\OrganizationFilter;
use App\Model\OrganizationList\OrganizationSorting;
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
        OrganizationFilter $filters,
        OrganizationSorting $sorting
    ): Pagerfanta
    {
        $builder = $this->createQueryBuilder('organization');

        $filters->apply($builder);
        $sorting->apply($builder);

        $paginator = new Pagerfanta(new DoctrineORMAdapter($builder->getQuery()));
        $paginator->setMaxPerPage($filters->getPerPage());
        $paginator->setCurrentPage($filters->getCurrentPage());

        return $paginator;
    }
}
