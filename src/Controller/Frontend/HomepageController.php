<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use App\Repository\Category\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
    /** @var CategoryRepository */
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route(name="_app_homepage", path="/")
     *
     * @return Response
     */
    public function homeAction(): Response
    {
        $categories = $this->categoryRepository->getForHomepage();

        return $this->render('frontend/homepage/_homepage.html.twig', [
            'categories' => $categories,
        ]);
    }
}
