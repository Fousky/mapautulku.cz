<?php declare(strict_types = 1);

namespace App\Model\OrganizationList;

use App\Entity\Organization\Category;
use App\Model\Enum\OrganizationSortByEnum;
use App\Model\Enum\SortOrderEnum;
use Consistence\Enum\InvalidEnumValueException;
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
    protected $page = 1;

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

    /** @var string */
    protected $sort = OrganizationSortByEnum::BY_NAME . '-' . SortOrderEnum::ORDER_ASC;

    protected $sortBy = OrganizationSortByEnum::BY_NAME;
    protected $sortOrder = SortOrderEnum::ORDER_ASC;

    public static $sortingChoices = [
        'Název (a-z)' => OrganizationSortByEnum::BY_NAME . '-' . SortOrderEnum::ORDER_ASC,
        'Název (z-a)' => OrganizationSortByEnum::BY_NAME . '-' . SortOrderEnum::ORDER_DESC,
        'Datum (nejnovější)' => OrganizationSortByEnum::BY_DATE . '-' . SortOrderEnum::ORDER_DESC,
        'Datum (nejstarší)' => OrganizationSortByEnum::BY_DATE . '-' . SortOrderEnum::ORDER_ASC,
    ];

    public static $perPageChoices = [
        self::DEFAULT_PER_PAGE => self::DEFAULT_PER_PAGE,
        24 => 24,
        48 => 48,
    ];

    private static $sortingPropertyMap = [
        OrganizationSortByEnum::BY_NAME => 'organization.name',
        OrganizationSortByEnum::BY_DATE => 'organization.createdAt',
    ];

    /** @var array|null */
    private $_filters;

    public function create(Request $request, ?Category $category): self
    {
        $filter = new static();

        return $filter
            ->setPage($this->extractPage($request))
            ->setPerPage($this->extractPerPage($request))
            ->setCategories($this->extractCategories($request, $category))
            ->setTypes($this->extractTypes($request))
            ->setRegions($this->extractRegions($request))
            ->setDistricts($this->extractDistricts($request))
            ->setMunicipalities($this->extractMunicipalities($request))
            ->setSortBy($this->extractSortBy($request))
            ->setSortOrder($this->extractSortOrder($request))
        ;
    }

    public function apply(QueryBuilder $builder): void
    {
        $this->applyCategories($builder);
        $this->applyTypes($builder);
        $this->applyRegions($builder);
        $this->applyDistricts($builder);
        $this->applyMunicipalities($builder);
        $this->applySorting($builder);
    }

    public function applySorting(QueryBuilder $builder): void
    {
        if (!array_key_exists($this->getSortBy(), self::$sortingPropertyMap)) {
            throw new \RuntimeException(sprintf(
                'SortBy `%s` is not implemented in %s.',
                $this->getSortBy(),
                __METHOD__
            ));
        }

        $builder
            ->orderBy(
                self::$sortingPropertyMap[$this->getSortBy()],
                $this->getSortOrder()
            );
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
        return $request->query->getInt('page', 1);
    }

    private function extractPerPage(Request $request): int
    {
        $filters = $this->extractFilters($request);

        try {
            $perPage = array_key_exists('perPage', $filters) ? (int) $filters['perPage'] : self::DEFAULT_PER_PAGE;
        } catch (\Throwable $exception) {
            $perPage = self::DEFAULT_PER_PAGE;
        }

        return $perPage;
    }

    private function extractCategories(Request $request, ?Category $category): array
    {
        $filters = $this->extractFilters($request);
        $categories = array_key_exists('categories', $filters) ? (array) $filters['categories'] : [];

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
        $filters = $this->extractFilters($request);
        $types = array_key_exists('types', $filters) ? (array) $filters['types'] : [];

        try {
            Type::checkType($types, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $types = [];
        }

        return $types;
    }

    private function extractRegions(Request $request): array
    {
        $filters = $this->extractFilters($request);
        $regions = array_key_exists('regions', $filters) ? (array) $filters['regions'] : [];

        try {
            Type::checkType($regions, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $regions = [];
        }

        return $regions;
    }

    private function extractDistricts(Request $request): array
    {
        $filters = $this->extractFilters($request);
        $districts = array_key_exists('districts', $filters) ? (array) $filters['districts'] : [];

        try {
            Type::checkType($districts, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $districts = [];
        }

        return $districts;
    }

    private function extractMunicipalities(Request $request): array
    {
        $filters = $this->extractFilters($request);
        $municipalities = array_key_exists('municipalities', $filters) ? (array) $filters['municipalities'] : [];

        try {
            Type::checkType($municipalities, 'string[]');
        } catch (InvalidArgumentTypeException $exception) {
            $municipalities = [];
        }

        return $municipalities;
    }

    private function extractFilters(Request $request): array
    {
        if ($this->_filters === null) {
            $this->_filters = (array) $request->query->get(OrganizationFilterFormType::NAME, []);
        }

        return $this->_filters;
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

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        if ($page <= 1) {
            $page = 1;
        }

        $this->page = $page;

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

    private function extractSortBy(Request $request): string
    {
        $sort = $this->extractSort($request);

        $sortBy = OrganizationSortByEnum::BY_NAME;

        if (strpos($sort, '-') !== false) {
            [$sortBy, ] = explode('-', $sort);
        }

        return $sortBy;
    }

    private function extractSortOrder(Request $request): string
    {
        $sort = $this->extractSort($request);

        $sortOrder = SortOrderEnum::ORDER_ASC;

        if (strpos($sort, '-') !== false) {
            [, $sortOrder] = explode('-', $sort);
        }

        return $sortOrder;
    }

    private function extractSort(Request $request): string
    {
        $filters = $this->extractFilters($request);

        return array_key_exists('sort', $filters)
            ? $filters['sort']
            : OrganizationSortByEnum::BY_NAME .'-'. SortOrderEnum::ORDER_ASC;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function setSortBy(string $sortBy): self
    {
        try {
            OrganizationSortByEnum::checkValue($sortBy);
        } catch (InvalidEnumValueException $exception) {
            $sortBy = OrganizationSortByEnum::BY_NAME;
        }

        $this->sortBy = $sortBy;

        return $this;
    }

    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    public function setSortOrder(string $sortOrder): self
    {
        try {
            SortOrderEnum::checkValue($sortOrder);
        } catch (InvalidEnumValueException $exception) {
            $sortOrder = SortOrderEnum::ORDER_ASC;
        }

        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
