<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use App\Model\OrganizationList\OrganizationListFacade;
use App\Repository\Category\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationController extends AbstractController
{
    /** @var CategoryRepository */
    protected $categoryRepository;

    /** @var OrganizationListFacade */
    protected $listFacade;

    public function __construct(
        CategoryRepository $categoryRepository,
        OrganizationListFacade $listFacade
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->listFacade = $listFacade;
    }

    /**
     * @Route(name="_app_organizations", path="/utulky-{slug}")
     *
     * @param Request $request
     * @param string|null $slug
     * @return Response
     */
    public function indexAction(Request $request, ?string $slug = null): Response
    {
        $category = $this->categoryRepository->findBySlug($slug);
        $paginator = $this->listFacade->getPaginator($request, $category);

        return $this->render('frontend/organization/_list.html.twig', [
            'category' => $category,
            'paginator' => $paginator,
        ]);
    }
}
