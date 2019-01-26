<?php declare(strict_types = 1);

namespace App\Admin\Organization;

use App\Entity\Geo\DistrictZipCode;
use App\Entity\Organization\Organization;
use App\Model\Doctrine\PointFormType;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
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
        $organization = $this->getSubject();

        $form
            ->with('Základní údaje', ['class' => 'col-md-6'])
                ->add('name', null, [
                    'label' => 'Název organizace',
                ])
                ->add('slug', null, [
                    'label' => 'URL slug',
                    'required' => false,
                ])
                ->add('public', null, [
                    'label' => 'Publikováno pro veřejnost?',
                ])
                ->add('crn', null, [
                    'label' => 'IČ',
                ])
                ->add('tin', null, [
                    'label' => 'DIČ',
                ])
                ->add('parent', ModelAutocompleteType::class, [
                    'label' => 'Mateřská organizace',
                    'property' => ['crn', 'name', 'email', 'phone'],
                    'required' => false,
                    'minimum_input_length' => 1,
                    'route' => [
                        'name' => 'sonata_admin_retrieve_autocomplete_items',
                        'parameters' => [
                            'except' => $organization instanceof Organization
                                ? $organization->getId()->toString()
                                : null,
                        ],
                    ],
                    'callback' => function (OrganizationAdmin $admin, $property, $value) {
                        $exceptId = $admin->getRequest()->get('except');

                        /** @var ProxyQuery $proxy */
                        $proxy = $admin->getDatagrid()->getQuery();

                        /** @var QueryBuilder $builder */
                        $builder = $proxy->getQueryBuilder();
                        $alias = $builder->getAllAliases()[0];

                        $builder
                            // disallow current org.
                            ->andWhere($alias . '.id != :id')
                            // disallow already linked to this parent org.
                            ->leftJoin($alias . '.parent', 'parent')
                            ->andWhere('(parent.id IS NULL OR parent.id != :parent)')
                            ->setParameter('id', $exceptId)
                            ->setParameter('parent', $exceptId)
                        ;
                    },
                ])
            ->end()

            ->with('Lokalizace', ['class' => 'col-md-6'])
                ->add('address', null, [
                    'label' => 'Adresa',
                    'required' => false,
                    'attr' => [
                        'data-toggle' => 'gps-address',
                    ],
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
                ->add('zip', ModelAutocompleteType::class, [
                    'label' => 'PSČ',
                    'property' => ['zipCode'],
                    'minimum_input_length' => 1,
                    'to_string_callback' => function (DistrictZipCode $zip) {
                        return sprintf('%s %s, %s', $zip->getZipCode(), $zip->getCity(), $zip->getCityPart());
                    },
                    'required' => false,
                ])
                ->add('gps', PointFormType::class, [
                    'label' => false,
                    'required' => false,
                    'gps_button' => true,
                    'by_reference' => false,
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
            ->add('public', null, [
                'label' => 'Veřejná?',
                'editable' => true,
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
            ->add('public', null, [
                'label' => 'Veřejná?',
            ])
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
