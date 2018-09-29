<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use App\Entity\Organization\Organization;
use App\Model\OrganizationAdd\OrganizationAddFormType;
use App\Model\OrganizationAdd\OrganizationAddHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationAddController extends AbstractController
{
    /** @var OrganizationAddHandler */
    protected $addHandler;

    public function __construct(OrganizationAddHandler $addHandler)
    {
        $this->addHandler = $addHandler;
    }

    /**
     * @Route(name="_app_organization_add", path="/pridat-utulek")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request): Response
    {
        $organization = new Organization();

        $form = $this->createForm(OrganizationAddFormType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addHandler->handleForm($form);

            return $this->redirectToRoute('_app_organization_add_thanks');
        }

        return $this->render('frontend/organization/_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="_app_organization_add_thanks", path="/pridat-utulek/dekujeme")
     *
     * @return Response
     */
    public function thanksAction(): Response
    {
        return $this->render('frontend/organization/_add_thanks.html.twig');
    }
}
