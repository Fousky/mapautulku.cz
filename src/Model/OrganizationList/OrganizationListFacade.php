<?php declare(strict_types = 1);

namespace App\Model\OrganizationList;

use App\Entity\Organization\Category;
use App\Repository\Organization\OrganizationRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationListFacade
{
    /** @var OrganizationRepository */
    protected $organizationRepository;

    /** @var OrganizationFilter */
    protected $organizationFilter;

    /** @var OrganizationSorting */
    protected $organizationSorting;

    public function __construct(
        OrganizationRepository $organizationRepository,
        OrganizationFilter $organizationFilter,
        OrganizationSorting $organizationSorting
    ) {
        $this->organizationRepository = $organizationRepository;
        $this->organizationFilter = $organizationFilter;
        $this->organizationSorting = $organizationSorting;
    }

    public function getPaginator(Request $request, ?Category $category): Pagerfanta
    {
        $filters = $this->organizationFilter->create($request, $category);
        $sorting = $this->organizationSorting->create($request);

        return $this->organizationRepository->createFilteredPaginator($filters, $sorting);
    }
}
