<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use App\Model\Ares\AresClient;
use Symfony\Component\Form\FormInterface;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationAddHandler
{
    /** @var AresClient */
    protected $aresClient;

    /** @var OrganizationStorage */
    protected $organizationStorage;

    public function __construct(
        AresClient $aresClient,
        OrganizationStorage $organizationStorage
    ) {
        $this->aresClient = $aresClient;
        $this->organizationStorage = $organizationStorage;
    }

    public function handleFirstStep(FormInterface $form): void
    {
        $organization = $form->getData();

        if (!$organization instanceof Organization) {
            throw new \RuntimeException(sprintf(
                'Form data must be instance of %s',
                Organization::class
            ));
        }

        $crn = preg_replace('/\D/', '', (string) $organization->getCrn());

        if (empty($crn)) {
            return;
        }

        try {
            $this->aresClient->resolveOrganizationByCrn($crn, $organization);
            $this->organizationStorage->save($organization);
            /**
             * TODO: add flash message "crn resolved"?
             */
        } catch (\Exception $exception) {
            /**
             * TODO: send error reporting?
             */
            throw $exception;
        }
    }

    public function handleSecondStep(FormInterface $form): void
    {
        /**
         * TODO: handle
         */
    }

    public function handleFullStep(FormInterface $form): void
    {
        /**
         * TODO: handle
         */
    }
}
