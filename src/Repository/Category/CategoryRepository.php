<?php declare(strict_types = 1);

namespace App\Repository\Category;

use App\Entity\Organization\Category;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class CategoryRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Category::class));
    }

    /**
     * @return Category[]
     */
    public function getForHomepage(): array
    {
        return $this->createQueryBuilder('category')
            ->where('category.public = 1')
            ->getQuery()
            ->getResult();
    }

    public function findBySlug(?string $slug): ?Category
    {
        if ($slug === null) {
            return null;
        }

        $category = $this->findOneBy([
            'slug' => $slug,
            'public' => true,
        ]);

        if (!$category instanceof Category) {
            return null;
        }

        return $category;
    }
}
