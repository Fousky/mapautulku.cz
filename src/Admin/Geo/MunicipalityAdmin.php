<?php declare(strict_types = 1);

namespace App\Admin\Geo;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;

class MunicipalityAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_geo_municipality';
    protected $baseRoutePattern = 'geo/municipality';
    protected $datagridValues = [
        '_sort_by' => 'title',
        '_sort_order' => 'ASC',
    ];

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('title', null, [
                'label' => 'Název obce',
                'required' => true,
            ])
            ->add('district', ModelAutocompleteType::class, [
                'label' => 'Okres',
                'property' => ['title'],
                'minimum_input_length' => 1,
                'required' => true,
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('title', null, [
                'label' => 'Název obce',
            ])
            ->add('district', null, [
                'label' => 'Okres',
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
            ->add('title', null, [
                'label' => 'Název obce',
            ])
            ->add('district', ModelAutocompleteFilter::class, [
                'label' => 'Okres',
            ], ModelAutocompleteType::class, [
                'property' => ['title'],
                'multiple' => true,
                'minimum_input_length' => 2,
            ])
            ->add('district.region', ModelAutocompleteFilter::class, [
                'label' => 'Kraj',
            ], ModelAutocompleteType::class, [
                'property' => ['title'],
                'multiple' => true,
                'minimum_input_length' => 2,
            ])
        ;
    }
}
