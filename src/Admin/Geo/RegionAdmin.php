<?php declare(strict_types = 1);

namespace App\Admin\Geo;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class RegionAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_geo_region';
    protected $baseRoutePattern = 'geo/region';

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('title', null, [
                'label' => 'Název kraje',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('title', null, [
                'label' => 'Název kraje',
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
                'label' => 'Název kraje',
            ])
        ;
    }
}
