<?php declare(strict_types = 1);

namespace App\Controller\Frontend;

use App\Model\OrganizationAdd\OrganizationAddHandler;
use App\Model\OrganizationAdd\OrganizationFirstStepFormType;
use App\Model\OrganizationAdd\OrganizationFullStepFormType;
use App\Model\OrganizationAdd\OrganizationSecondStepFormType;
use App\Model\OrganizationAdd\OrganizationStorage;
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

    /** @var OrganizationStorage */
    protected $organizationStorage;

    public function __construct(
        OrganizationAddHandler $addHandler,
        OrganizationStorage $organizationStorage
    ) {
        $this->addHandler = $addHandler;
        $this->organizationStorage = $organizationStorage;
    }

    /**
     * @Route(name="_app_organization_add", path="/pridat-utulek")
     *
     * @param Request $request
     * @return Response
     */
    public function stepFirstAction(Request $request): Response
    {
        $organization = $this->organizationStorage->getOrCreateNew();

        $form = $this->createForm(OrganizationFirstStepFormType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->addHandler->handleFirstStep($form);
            } catch (\Exception $e) {
                return $this->redirectToRoute('_app_organization_add_full');
            }

            return $this->redirectToRoute('_app_organization_add_second');
        }

        return $this->render('frontend/organization/_add_step_first.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="_app_organization_add_second", path="/pridat-utulek/informace")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function stepSecondAction(Request $request): Response
    {
        $organization = $this->organizationStorage->getOrCreateNew();

        if ($organization->getCrn() === null) {
            return $this->redirectToRoute('_app_organization_add');
        }

        $form = $this->createForm(OrganizationSecondStepFormType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addHandler->handleSecondStep($form);

            return $this->redirectToRoute('_app_organization_add_thanks');
        }

        return $this->render('frontend/organization/_add_step_second.html.twig', [
            'form' => $form->createView(),
            'org' => $organization,
        ]);
    }

    /**
     * @Route(name="_app_organization_add_full", path="/pridat-utulek/vsechny-udaje")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function stepFullAction(Request $request): Response
    {
        $organization = $this->organizationStorage->getOrCreateNew();

        $form = $this->createForm(OrganizationFullStepFormType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addHandler->handleFullStep($form);

            return $this->redirectToRoute('_app_organization_add_thanks');
        }

        return $this->render('frontend/organization/_add_step_full.html.twig', [
            'form' => $form->createView(),
            'skipped' => $request->query->has('skip'),
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
