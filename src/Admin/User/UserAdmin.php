<?php declare(strict_types = 1);

namespace App\Admin\User;

use App\Entity\User\User;
use App\Model\Enum\RolesForUserEnum;
use App\Model\Security\ChangeUserPasswordResolver;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints as Assert;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = '_admin_user';
    protected $baseRoutePattern = 'user';

    /** @var ChangeUserPasswordResolver */
    protected $changePassword;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        ChangeUserPasswordResolver $changePassword
    ) {
        parent::__construct($code, $class, $baseControllerName);

        $this->changePassword = $changePassword;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('Osobní údaje', ['class' => 'col-md-6'])
                ->add('firstname', Type\TextType::class, [
                    'label' => 'Křestní jméno',
                    'required' => false,
                ])
                ->add('lastname', Type\TextType::class, [
                    'label' => 'Příjmení',
                    'required' => false,
                ])
            ->end();

        $form
            ->with('Login', ['class' => 'col-md-6'])
                ->add('email', Type\EmailType::class, [
                    'label' => 'E-mail',
                    'required' => true,
                ])
                ->add('plainPassword', Type\TextType::class, [
                    'label' => 'Nové heslo',
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ])
                ->add('enabled', null, [
                    'label' => 'Povolený?',
                    'help' => 'Může se uživatel přihlásit?',
                ])
                ->add('plainPassword', Type\TextType::class, [
                    'label' => 'Password',
                    'required' => false,
                    'help' => 'Pro změnu stačí vepsat nové heslo.',
                    'constraints' => [
                        new Assert\Length([
                            'min' => User::PASSWORD_LENGTH_MIN,
                            'max' => User::PASSWORD_LENGTH_MAX,
                        ]),
                    ],
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ])
        ;

        if ($this->id($this->getSubject()) === null) {
            $form->remove('plainPassword');
            $form->add('plainPassword', Type\TextType::class, [
                'label' => 'Heslo',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => User::PASSWORD_LENGTH_MIN,
                        'max' => User::PASSWORD_LENGTH_MAX,
                    ]),
                ],
            ]);
        }

        $form->end();

        $form
            ->with('Bezpečnost', ['class' => 'col-md-6'])
                ->add('roles', Type\ChoiceType::class, [
                    'label' => 'Role',
                    'choices' => $this->createRoleChoices(),
                    'expanded' => true,
                    'multiple' => true,
                ])
            ->end();
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('enabled', null, [
                'editable' => true,
            ])
            ->add('_action', null, [
                'label' => 'Actions',
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
            ->add('roles', 'doctrine_orm_callback', [
                'label' => 'Role (explicitní)',
                'callback' => [$this, 'filterByRoles'],
            ], Type\ChoiceType::class, [
                'choices' => $this->createRoleChoices(),
                'multiple' => true,
            ])
            ->add('email', null, [
                'label' => 'E-mail',
            ])
            ->add('enabled', null, [
                'label' => 'Povolen?',
            ])
        ;
    }

    /**
     * @param User $object
     */
    public function prePersist($object)
    {
        $this->resolvePassword($object);
    }

    /**
     * @param User $object
     */
    public function preUpdate($object)
    {
        $this->resolvePassword($object);
    }

    private function resolvePassword(User $user): void
    {
        $this->changePassword->resolvePlainPassword($user);
    }

    protected function createRoleChoices(): array
    {
        return RolesForUserEnum::getChoices();
    }

    public function filterByRoles(ProxyQuery $queryBuilder, $alias, $field, $value): ?bool
    {
        if (!is_array($value) || !array_key_exists('value', $value) || empty($value['value'])) {
            return null;
        }

        $roles = (array) $value['value'];

        /** @var \Doctrine\ORM\QueryBuilder $builder */
        $builder = $queryBuilder->getQueryBuilder();

        $parts = [];
        $params = [];
        $x = 1;
        foreach ($roles as $role) {
            $key = ':role'.$x;
            $parts[] = sprintf('%s.roles LIKE %s', $alias, $key);
            $params[$key] = '%' . $role . '%';
            $x++;
        }

        $query = '(' . implode($parts, ' OR ') . ')';

        $builder->andWhere($query);

        foreach ($params as $index => $param) {
            $builder->setParameter($index, $param);
        }

        return true;
    }
}
