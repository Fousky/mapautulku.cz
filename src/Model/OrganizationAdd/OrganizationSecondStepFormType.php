<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationSecondStepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hasCategories', HasCategoriesType::class, [
                'label' => 'Zvířata, kterým pomáhají',
                'required' => false,
                'by_reference' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail:',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefon:',
                'required' => false,
            ])
            ->add('www', TextType::class, [
                'label' => 'Odkaz na internetové stránky:',
                'required' => false,
            ])
            ->add('facebook', TextType::class, [
                'label' => 'Odkaz na Facebook:',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Přidat organizaci',
                'attr' => [
                    'class' => 'button preview',
                ],
            ])
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
