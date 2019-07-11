<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use App\Model\Ares\AresClient;
use App\Model\Ares\AresToOrganizationResolver;
use App\Repository\Organization\OrganizationRepository;
use Symfony\Component\Form\FormInterface;

class OrganizationAddHandler
{
    /** @var AresClient */
    protected $aresClient;

    /** @var AresToOrganizationResolver */
    protected $aresToOrganizationResolver;

    /** @var OrganizationStorage */
    protected $organizationStorage;

    /** @var OrganizationRepository */
    protected $organizationRepository;

    /** @var OrganizationAddMailer */
    protected $addMailer;

    public function __construct(
        AresClient $aresClient,
        AresToOrganizationResolver $aresToOrganizationResolver,
        OrganizationStorage $organizationStorage,
        OrganizationRepository $organizationRepository,
        OrganizationAddMailer $addMailer
    ) {
        $this->aresClient = $aresClient;
        $this->aresToOrganizationResolver = $aresToOrganizationResolver;
        $this->organizationStorage = $organizationStorage;
        $this->organizationRepository = $organizationRepository;
        $this->addMailer = $addMailer;
    }

    public function handleFirstStep(FormInterface $form): void
    {
        $organization = $this->getOrganization($form);

        $crn = preg_replace('/\D/', '', (string) $organization->getCrn());

        if (!empty($crn)) {
            $aresReponse = $this->aresClient->getOrganizationByCrn($crn);
            $this->aresToOrganizationResolver->resolve($crn, $organization, $aresReponse);
        }

        $this->organizationStorage->save($organization);
    }

    public function handleSecondStep(FormInterface $form): void
    {
        $organization = $this->getOrganization($form);

        $this->organizationRepository->mergeLocalities($organization);
        $this->organizationRepository->save($organization);

        $this->organizationStorage->abandon();

        $this->addMailer->notifyOrganizationAddRecipients($organization);
    }

    public function handleFullStep(FormInterface $form): void
    {
        $organization = $this->getOrganization($form);

        $this->organizationRepository->mergeLocalities($organization);
        $this->organizationRepository->save($organization);

        $this->organizationStorage->abandon();

        $this->addMailer->notifyOrganizationAddRecipients($organization);
    }

    private function getOrganization(FormInterface $form): Organization
    {
        $organization = $form->getData();

        if (!$organization instanceof Organization) {
            throw new \RuntimeException(sprintf(
                'Form data must be instance of %s',
                Organization::class
            ));
        }

        return $organization;
    }
}
