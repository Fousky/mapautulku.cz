<?php declare(strict_types = 1);

namespace App\Model\OrganizationList;

use App\Repository\Category\CategoryRepository;
use App\Repository\Geo\RegionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationFilterFormType extends AbstractType
{
    public const NAME = '_f';

    /** @var CategoryRepository */
    protected $categoryRepository;

    /** @var RegionRepository */
    protected $regionRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        RegionRepository $regionRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->regionRepository = $regionRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('categories', ChoiceType::class, [
                'label' => 'Kategorie',
                'choices' => $this->categoryRepository->createChoices(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            // ->add('types')
            ->add('regions', ChoiceType::class, [
                'label' => 'Kraj',
                'choices' => $this->regionRepository->createChoices(),
                'required' => false,
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('districts', ChoiceType::class, [
                'label' => 'Okres',
                'choices' => [],
                'required' => false,
                'multiple' => true,
            ])
            ->add('municipalities', ChoiceType::class, [
                'label' => 'Obec',
                'choices' => [],
                'required' => false,
                'multiple' => true,
            ])
            ->add('sort', ChoiceType::class, [
                'label' => 'Seřadit',
                'choices' => OrganizationFilter::$sortingChoices,
                'multiple' => false,
                'required' => true,
                'expanded' => false,
            ])
            ->add('perPage', ChoiceType::class, [
                'label' => 'Položek na stránce',
                'choices' => OrganizationFilter::$perPageChoices,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrovat',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => OrganizationFilter::class,
            ])
        ;
    }

    public static function getFullName(string $property): string
    {
        return sprintf('%s[%s]', self::NAME, $property);
    }
}
