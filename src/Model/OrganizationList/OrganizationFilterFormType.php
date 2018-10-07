<?php declare(strict_types = 1);

namespace App\Model\OrganizationList;

use App\Entity\Geo\District;
use App\Repository\Category\CategoryRepository;
use App\Repository\Geo\DistrictRepository;
use App\Repository\Geo\RegionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    /** @var DistrictRepository */
    protected $districtRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        RegionRepository $regionRepository,
        DistrictRepository $districtRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->regionRepository = $regionRepository;
        $this->districtRepository = $districtRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('name', TextType::class, [
                'label' => 'Název organizace?',
                'required' => false,
            ])
            ->add('categories', ChoiceType::class, [
                'label' => 'Kategorie',
                'choices' => $this->categoryRepository->createChoices(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('region', ChoiceType::class, [
                'label' => 'Kraj',
                'choices' => $this->regionRepository->createChoices(),
                'required' => false,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('district', ChoiceType::class, [
                'label' => 'Okres',
                'choices' => $this->districtRepository->createChoices(),
                'choice_label' => function ($district, $label) {
                    if ($district instanceof District) {
                        return $district->getTitle();
                    }

                    if (is_string($district)) {
                        return $district;
                    }

                    return $label;
                },
                'choice_value' => function ($district) {
                    return $district instanceof District ? $district->getId()->toString() : $district;
                },
                'choice_attr' => function ($district, $label) {
                    if ($district === null || is_string($district)) {
                        return [];
                    }

                    return [
                        'data-region' => $district instanceof District && $district->getRegion()
                            ? $district->getRegion()->getId()->toString()
                            : null,
                    ];
                },
                'required' => false,
                'multiple' => false,
                'expanded' => false,
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
