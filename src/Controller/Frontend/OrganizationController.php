<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use App\Repository\Category\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationController extends AbstractController
{
    /** @var CategoryRepository */
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route(name="_app_organizations", path="/utulky-{slug}")
     *
     * @param string|null $slug
     * @return Response
     */
    public function indexAction(?string $slug = null): Response
    {
        $category = $this->categoryRepository->findBySlug($slug);

        return $this->render('frontend/organization/_list.html.twig', [
            'category' => $category,
        ]);
    }
}
