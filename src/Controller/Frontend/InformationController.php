<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route(name="_app_info_contact", path="/napiste-nam")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function contactAction(Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        return $this->render('frontend/information/_contact_us.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
