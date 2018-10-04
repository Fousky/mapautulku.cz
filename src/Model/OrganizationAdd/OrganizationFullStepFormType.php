<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationFullStepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Název organizace *:',
                'required' => true,
            ])
            ->add('crn', TextType::class, [
                'label' => 'IČ:',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'help' => 'Vyplňte vše, co víte - ulice, č.p., město i PSČ',
                'required' => false,
            ])
            ->add('hasCategories', HasCategoriesType::class, [
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Zvolte alespoň 1 kategorii zvířat, které organizace pomáhá',
                    ]),
                ],
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
                'label' => 'Přidat útulek',
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
