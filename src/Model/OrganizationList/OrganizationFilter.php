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
    public const DEFAULT_PER_PAGE = 6;

    /** @var int */
    protected $page = 1;

    /** @var int */
    protected $perPage = self::DEFAULT_PER_PAGE;

    /** @var string|null */
    protected $name;

    /** @var string[] */
    protected $categories = [];

    /** @var string[] */
    protected $types = [];

    /** @var string|null */
    protected $region;

    /** @var string|null */
    protected $district;

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
        self::DEFAULT_PER_PAGE . ' na stránce' => self::DEFAULT_PER_PAGE,
        '24 na stránce' => 24,
        '48 na stránce' => 48,
    ];

    private static $sortingPropertyMap = [
        OrganizationSortByEnum::BY_NAME => 'organization.name',
        OrganizationSortByEnum::BY_DATE => 'organization.createdAt',
    ];

    /** @var array|null */
    private $_filters;

    public function isActive(): bool
    {
        return $this->getPage() !== 1
            || $this->getName() !== null
            || $this->getDistrict() !== null
            || $this->getRegion() !== null
            || $this->getSortBy() !== OrganizationSortByEnum::BY_NAME
            || $this->getSortOrder() !== SortOrderEnum::ORDER_ASC
            || $this->getPerPage() !== self::DEFAULT_PER_PAGE
            || !empty($this->getCategories())
        ;
    }

    public function create(Request $request, ?Category $category): self
    {
        $filter = new static();

        return $filter
            ->setPage($this->extractPage($request))
            ->setPerPage($this->extractPerPage($request))
            ->setName($this->extractName($request))
            ->setCategories($this->extractCategories($request, $category))
            ->setTypes($this->extractTypes($request))
            ->setRegion($this->extractRegion($request))
            ->setDistrict($this->extractDistrict($request))
            ->setSortBy($this->extractSortBy($request))
            ->setSortOrder($this->extractSortOrder($request))
        ;
    }

    public function apply(QueryBuilder $builder): void
    {
        $this->applyCategories($builder);
        $this->applyRegion($builder);
        $this->applyDistrict($builder);
        $this->applyName($builder);
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
        if (!empty($categories)) {
            $builder
                ->join('organization.hasCategories', 'hasCategories')
                ->join('hasCategories.category', 'category')
                ->where($builder->expr()->in('category.slug', $categories));
        }
    }

    private function applyRegion(QueryBuilder $builder): void
    {
        $region = $this->getRegion();
        if ($region) {
            $builder
                ->join('organization.region', 'region')
                ->andWhere('region.id = :region')
                ->setParameter('region', $region);
        }
    }

    private function applyDistrict(QueryBuilder $builder): void
    {
        $district = $this->getDistrict();
        if ($district) {
            $builder
                ->join('organization.district', 'district')
                ->andWhere('district.id = :district')
                ->setParameter('district', $district);
        }
    }

    private function applyName(QueryBuilder $builder): void
    {
        $name = $this->getName();
        if ($name) {
            $builder
                ->andWhere('organization.name LIKE :orgName')
                ->setParameter('orgName', '%' . $name . '%');
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

    private function extractRegion(Request $request): ?string
    {
        $filters = $this->extractFilters($request);
        $region = array_key_exists('region', $filters) ? (string) $filters['region'] : null;

        try {
            Type::checkType($region, 'string|null');
        } catch (InvalidArgumentTypeException $exception) {
            $region = null;
        }

        return $region;
    }

    private function extractDistrict(Request $request): ?string
    {
        $filters = $this->extractFilters($request);
        $district = array_key_exists('district', $filters) ? (string) $filters['district'] : null;

        try {
            Type::checkType($district, 'string|null');
        } catch (InvalidArgumentTypeException $exception) {
            $district = null;
        }

        return $district;
    }

    private function extractName(Request $request): ?string
    {
        $filters = $this->extractFilters($request);

        return array_key_exists('name', $filters) ? (string) $filters['name']: null;
    }

    private function extractFilters(Request $request): array
    {
        if ($this->_filters === null) {
            $this->_filters = (array) $request->query->get(OrganizationFilterFormType::NAME, []);
        }

        return $this->_filters;
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

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): self
    {
        $this->district = $district;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
