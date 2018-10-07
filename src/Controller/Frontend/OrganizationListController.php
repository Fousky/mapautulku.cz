<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use App\Model\OrganizationList\OrganizationFilter;
use App\Model\OrganizationList\OrganizationFilterFormType;
use App\Repository\Category\CategoryRepository;
use App\Repository\Organization\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationListController extends AbstractController
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var CategoryRepository */
    protected $categoryRepository;

    /** @var OrganizationRepository */
    protected $organizationRepository;

    /** @var OrganizationFilter */
    protected $organizationFilter;

    public function __construct(
        FormFactoryInterface $formFactory,
        CategoryRepository $categoryRepository,
        OrganizationRepository $organizationRepository,
        OrganizationFilter $organizationFilter
    ) {
        $this->formFactory = $formFactory;
        $this->categoryRepository = $categoryRepository;
        $this->organizationRepository = $organizationRepository;
        $this->organizationFilter = $organizationFilter;
    }

    /**
     * @Route(name="_app_organizations_list", path="/utulky-{slug}")
     *
     * @param Request $request
     * @param string|null $slug
     * @return Response
     */
    public function indexAction(Request $request, ?string $slug = null): Response
    {
        $category = $this->categoryRepository->findBySlug($slug);
        $filters = $this->organizationFilter->create($request, $category);
        $paginator = $this->organizationRepository->createFilteredPaginator($filters);

        $filterForm = $this
            ->formFactory
            ->createNamed(OrganizationFilterFormType::NAME, OrganizationFilterFormType::class, $filters)
            ->handleRequest($request);

        return $this->render('frontend/organization/_list.html.twig', [
            'category' => $category,
            'paginator' => $paginator,
            'filterForm' => $filterForm->createView(),
            'filters' => $filters,
            'resetFiltersLink' => $this->generateUrl('_app_organizations_list', [
                'slug' => $slug,
            ]),
        ]);
    }
}
