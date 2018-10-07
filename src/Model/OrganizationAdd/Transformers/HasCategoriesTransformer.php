<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd\Transformers;

use App\Entity\Organization\OrganizationHasCategory;
use App\Repository\Category\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class HasCategoriesTransformer implements DataTransformerInterface
{
    /** @var CategoryRepository */
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function transform($value)
    {
        $result = [];

        if ($value === null) {
            return $result;
        }

        if (empty($value)) {
            return $result;
        }

        return $result;
    }

    public function reverseTransform($value)
    {
        $result = new ArrayCollection();

        if ($value === null || empty($value)) {
            return $result;
        }

        if ($value instanceof ArrayCollection) {
            return $value;
        }

        foreach ($value as $slug) {
            $category = $this->categoryRepository->findBySlug($slug);

            if ($category) {
                $hasCategory = new OrganizationHasCategory();
                $hasCategory->setCategory($category);

                $result->add($hasCategory);
            }
        }

        return $result;
    }
}
