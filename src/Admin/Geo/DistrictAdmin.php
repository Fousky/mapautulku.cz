<?php declare(strict_types = 1);

namespace App\Admin\Geo;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class DistrictAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_geo_district';
    protected $baseRoutePattern = 'geo/district';

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('title', null, [
                'label' => 'Název okresu',
            ])
            ->add('region', ModelAutocompleteType::class, [
                'label' => 'Kraj',
                'property' => ['title'],
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('title', null, [
                'label' => 'Název okresu',
            ])
            ->add('region', null, [
                'label' => 'Kraj',
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
                'label' => 'Název okresu',
            ])
            ->add('region', null, [
                'label' => 'Kraj',
            ], null, [
                'multiple' => true,
            ])
        ;
    }
}
