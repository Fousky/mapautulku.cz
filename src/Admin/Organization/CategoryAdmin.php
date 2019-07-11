<?php declare(strict_types = 1);

namespace App\Admin\Organization;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_category';
    protected $baseRoutePattern = 'category';

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', null, [
                'label' => 'Název kategorie',
            ])
            ->add('nameInAkuzativ', null, [
                'label' => 'Název kategorie (4. pád)',
            ])
            ->add('public', null, [
                'label' => 'Veřejná?',
            ])
            ->add('icon', null, [
                'label' => 'Ikona',
                'help' => 'Sada z Iconsmind',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('name', null, [
                'label' => 'Název kategorie',
            ])
            ->add('nameInAkuzativ', null, [
                'label' => 'Název kategorie (4. pád)',
            ])
            ->add('public', null, [
                'label' => 'Veřejná?',
                'editable' => true,
            ])
            ->add('icon', null, [
                'label' => 'Ikona',
                'editable' => true,
            ])
            ->add('_action', null, [
                'label' => 'Možnosti',
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name', null, [
                'label' => 'Název kategorie',
            ])
            ->add('public', null, [
                'label' => 'Veřejná?',
            ])
        ;
    }
}
