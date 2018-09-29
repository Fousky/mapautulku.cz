<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class InformationController extends AbstractController
{
    /**
     * @Route(name="_app_info_about", path="/o-projektu")
     *
     * @return Response
     */
    public function aboutAction(): Response
    {
        return $this->render('frontend/information/_about_project.html.twig');
    }
}
