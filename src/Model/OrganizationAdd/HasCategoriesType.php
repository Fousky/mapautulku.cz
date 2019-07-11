<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Model\OrganizationAdd\Transformers\HasCategoriesTransformer;
use App\Repository\Category\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HasCategoriesType extends AbstractType
{
    /** @var CategoryRepository */
    protected $categoryRepository;

    /** @var HasCategoriesTransformer */
    protected $transformer;

    public function __construct(
        CategoryRepository $categoryRepository,
        HasCategoriesTransformer $transformer
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => $this->categoryRepository->createChoices(),
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
