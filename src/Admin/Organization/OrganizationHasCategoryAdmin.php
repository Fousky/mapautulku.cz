<?php declare(strict_types = 1);

namespace App\Admin\Organization;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganizationHasCategoryAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_organization_has_category';
    protected $baseRoutePattern = 'organization-has-category';

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('position', HiddenType::class, [
                'label' => 'Pozice',
            ])
            ->add('category', null, [
                'label' => 'Zvolte kategorii',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
    }
}
