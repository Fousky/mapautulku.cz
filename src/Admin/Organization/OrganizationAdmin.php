<?php declare(strict_types = 1);

namespace App\Admin\Organization;

use App\Model\Doctrine\PointFormType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_organization';
    protected $baseRoutePattern = 'organization';

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('Základní údaje', ['class' => 'col-md-6'])
                ->add('name', null, [
                    'label' => 'Název organizace',
                ])
                ->add('slug', null, [
                    'label' => 'URL slug',
                ])
                ->add('crn', null, [
                    'label' => 'IČ',
                ])
                ->add('tin', null, [
                    'label' => 'DIČ',
                ])
            ->end()

            ->with('Lokalizace', ['class' => 'col-md-6'])
                ->add('address', null, [
                    'label' => 'Adresa',
                    'required' => false,
                ])
                ->add('municipality', ModelAutocompleteType::class, [
                    'label' => 'Město',
                    'property' => ['title'],
                    'minimum_input_length' => 1,
                    'required' => false,
                ])
                ->add('district', ModelAutocompleteType::class, [
                    'label' => 'Okres',
                    'property' => ['title'],
                    'minimum_input_length' => 1,
                    'required' => false,
                ])
                ->add('region', ModelAutocompleteType::class, [
                    'label' => 'Kraj',
                    'property' => ['title'],
                    'minimum_input_length' => 1,
                    'required' => false,
                ])
                ->add('gps', PointFormType::class, [
                    'label' => false,
                    'required' => false,
                ])
            ->end()

            ->with('Kontakt', ['class' => 'col-md-6'])
                ->add('email', null, [
                    'label' => 'E-mail',
                    'required' => false,
                ])
                ->add('phone', null, [
                    'label' => 'Telefon',
                    'required' => false,
                ])
                ->add('www', null, [
                    'label' => 'WWW stránky',
                    'required' => false,
                ])
                ->add('facebook', null, [
                    'label' => 'Facebook',
                    'required' => false,
                ])
            ->end()

            ->with('kategorie', ['class' => 'col-md-6'])
                ->add('hasCategories', CollectionType::class, [
                    'label' => false,
                    'by_reference' => false,
                    'required' => false,
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                ])
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('name', null, [
                'label' => 'Název organizace',
            ])
            ->add('crn', null, [
                'label' => 'IČ',
            ])
            ->add('links', null, [
                'label' => 'Odkazy',
                'template' => 'admin/CRUD/list__organization_links.html.twig',
            ])
            ->add('hasCategories', null, [
                'label' => 'Kategorie',
            ])
            ->add('municipality', null, [
                'label' => 'Město',
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
                'label' => 'Název organizace',
            ])
            ->add('crn', null, [
                'label' => 'IČ',
            ])
            ->add('email', null, [
                'label' => 'E-mail',
            ])
            ->add('phone', null, [
                'label' => 'Telefon',
            ])
            ->add('region', null, [
                'label' => 'Lokalita - Kraj',
            ], null, [
                'multiple' => true,
            ])
            ->add('district', null, [
                'label' => 'Lokalita - Okres',
            ], null, [
                'multiple' => true,
            ])
            ->add('municipality', ModelAutocompleteFilter::class, [
                'label' => 'Lokalita - Obec',
            ], ModelAutocompleteType::class, [
                'property' => ['title'],
                'multiple' => true,
                'minimum_input_length' => 1,
            ])
            ->add('address', null, [
                'label' => 'Adresa',
            ])
            ->add('hasCategories.category', null, [
                'label' => 'Kategorie',
            ], null, [
                'multiple' => true,
            ])
        ;
    }
}
