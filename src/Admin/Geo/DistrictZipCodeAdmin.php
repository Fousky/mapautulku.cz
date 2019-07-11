<?php declare(strict_types = 1);

namespace App\Admin\Geo;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;

class DistrictZipCodeAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_geo_district_zip_code';
    protected $baseRoutePattern = 'geo/district-zip-code';

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('zipCode', null, [
                'label' => 'PSČ',
            ])
            ->add('district', ModelAutocompleteType::class, [
                'label' => 'Okres',
                'property' => ['title'],
            ])
            ->add('municipality', ModelAutocompleteType::class, [
                'label' => 'Obec',
                'property' => ['title'],
            ])
            ->add('city', null, [
                'label' => 'Název obce',
            ])
            ->add('cityPart', null, [
                'label' => 'Název části obce',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('district', null, [
                'label' => 'Okres',
            ])
            ->add('municipality', null, [
                'label' => 'Obec',
            ])
            ->add('zipCode', null, [
                'label' => 'PSČ',
            ])
            ->add('city', null, [
                'label' => 'Název obce',
            ])
            ->add('cityPart', null, [
                'label' => 'Název části obce',
            ])
            ->add('_action', null, [
                'label' => 'Možnosti',
                'actions' => [
                    'edit' => [],
                ],
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('district', ModelAutocompleteFilter::class, [
                'label' => 'Okres',
            ], ModelAutocompleteType::class, [
                'multiple' => true,
                'property' => ['title'],
            ])
            ->add('municipality', ModelAutocompleteFilter::class, [
                'label' => 'Obec',
            ], ModelAutocompleteType::class, [
                'multiple' => true,
                'property' => ['title'],
            ])
            ->add('zipCode', null, [
                'label' => 'PSČ',
            ])
            ->add('city', null, [
                'label' => 'Název obce',
            ])
            ->add('cityPart', null, [
                'label' => 'Název části obce',
            ])
        ;
    }
}
