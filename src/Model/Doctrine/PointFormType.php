<?php declare(strict_types = 1);

namespace App\Model\Doctrine;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class PointFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude', TextType::class, [
                'label' => 'Zeměpisná šířka',
                'required' => false,
                'attr' => [
                    'data-toggle' => 'gps-latitude',
                ],
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Zeměpisná délka',
                'required' => false,
                'attr' => [
                    'data-toggle' => 'gps-longitude',
                ],
            ])
        ;

        if ($options['gps_button']) {
            $builder->add('button', ButtonType::class, [
                'label' => 'GPS',
                'attr' => [
                    'class' => 'btn btn-default',
                    'data-toggle' => 'gps-button',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Point::class,
                'gps_button' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'gps';
    }
}
