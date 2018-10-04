<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use App\Model\OrganizationAdd\Constraint\Crn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationFirstStepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('crn', TextType::class, [
                'label' => 'IČ:',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Crn(),
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Organization::class,
            ])
        ;
    }
}
