<?php declare(strict_types = 1);

namespace App\Model\OrganizationList;

use App\Entity\Organization\Category;
use Consistence\InvalidArgumentTypeException;
use Consistence\Type\Type;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationFilter
{
    public const DEFAULT_PER_PAGE = 12;

    /** @var int */
    protected $currentPage = 1;

    /** @var int */
    protected $perPage = self::DEFAULT_PER_PAGE;

    /** @var string[] */
    protected $categories = [];

    /** @var string[] */
    protected $types = [];

    /** @var string[] */
    protected $regions = [];

    /** @var string[] */
    protected $districts = [];

    /** @var string[] */
    protected $municipalities = [];

    public function create(Request $request, ?Category $category): self
    {
        $filter = new static();

        return $filter
            ->setCurrentPage($this->extractPage($request))
            ->setPerPage($this->extractPerPage($request))
            ->setCategories($this->extractCategories($request, $category))
            ->setTypes($this->extractTypes($request))
            ->setRegions($this->extractRegions($request))
            ->setDistricts($this->extractDistricts($request))
            ->setMunicipalities($this->extractMunicipalities($request))
        ;
    }

    public function apply(QueryBuilder $builder): void
    {
        $this->applyCategories($builder);
        $this->applyTypes($builder);
        $this->applyRegions($builder);
        $this->applyDistricts($builder);
        $this->applyMunicipalities($builder);
    }

    private function applyCategories(QueryBuilder $builder): void
    {
        $categories = $this->getCategories();
        if ($categories) {
            $builder
                ->join('organization.hasCategories', 'hasCategories')
                ->join('hasCategories.category', 'category')
                ->where($builder->expr()->in('category.slug', $categories));
        }
    }

    private function applyTypes(QueryBuilder $builder): void
    {
        $types = $this->getTypes();
        if ($types) {
            // todo: resolve types.
        }
    }

    private function applyRegions(QueryBuilder $builder): void
    {
        $regions = $this->getRegions();
        if ($regions) {
            $builder
                ->join('organization.region', 'region')
                ->andWhere($builder->expr()->in('region.id', $regions));
        }
    }

    private function applyDistricts(QueryBuilder $builder): void
    {
        $districts = $this->getDistricts();
        if ($districts) {
            $builder
                ->join('organization.district', 'district')
                ->andWhere($builder->expr()->in('district.id', $districts));
        }
    }

    private function applyMunicipalities(QueryBuilder $builder): void
    {
        $municipalities = $this->getMunicipalities();
        if ($municipalities) {
            $builder
                ->join('organization.municipality', 'municipality')
                ->andWhere($builder->expr()->in('municipality.id', $municipalities));
        }
    }

    private function extractPage(Request $request): int
    {
        try {
            $page = $request->query->getInt('page', 1);
        } catch (\Throwable $exception) {
            $page = 1;
        }

        return $page;
    }

    private function extractPerPage(Request $request): int
    {
        try {
            $perPage = $request->query->getInt('perPage', self::DEFAULT_PER_PAGE);
        } catch (\Throwable $exception) {
            $perPage = self::DEFAULT_PER_PAGE;
        }

        return $perPage;
    }

    private function extractCategories(Request $request, ?Category $category): array
    {
        $categories = (array) $request->query->get('categories', []);

        try {
            Type::checkType($categories, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $categories = [];
        }

        if ($category && count($categories) === 0) {
            $categories[] = $category->getSlug();
        }

        return $categories;
    }

    private function extractTypes(Request $request): array
    {
        $types = (array) $request->get('types', []);

        try {
            Type::checkType($types, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $types = [];
        }

        return $types;
    }

    private function extractRegions(Request $request): array
    {
        $regions = (array) $request->get('regions', []);

        try {
            Type::checkType($regions, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $regions = [];
        }

        return $regions;
    }

    private function extractDistricts(Request $request): array
    {
        $districts = (array) $request->get('districts', []);

        try {
            Type::checkType($districts, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $districts = [];
        }

        return $districts;
    }

    private function extractMunicipalities(Request $request): array
    {
        $municipalities = (array) $request->get('municipalities', []);

        try {
            Type::checkType($municipalities, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $municipalities = [];
        }

        return $municipalities;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function setTypes(array $types): self
    {
        $this->types = $types;

        return $this;
    }

    public function getRegions(): array
    {
        return $this->regions;
    }

    public function setRegions(array $regions): self
    {
        $this->regions = $regions;

        return $this;
    }

    public function getDistricts(): array
    {
        return $this->districts;
    }

    public function setDistricts(array $districts): self
    {
        $this->districts = $districts;

        return $this;
    }

    public function getMunicipalities(): array
    {
        return $this->municipalities;
    }

    public function setMunicipalities(array $municipalities): self
    {
        $this->municipalities = $municipalities;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        if ($currentPage <= 1) {
            $currentPage = 1;
        }

        $this->currentPage = $currentPage;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): self
    {
        if ($perPage <= 1) {
            $perPage = 1;
        }

        $this->perPage = $perPage;

        return $this;
    }
}
