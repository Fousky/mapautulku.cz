<?php declare(strict_types = 1);

namespace App\Model\OrganizationList;

use App\Model\Enum\OrganizationSortByEnum;
use App\Model\Enum\SortOrderEnum;
use Consistence\Enum\InvalidEnumValueException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationSorting
{
    public const _URL_KEY_SORT = 'sort';

    protected $sortBy = OrganizationSortByEnum::BY_NAME;
    protected $sortOrder = SortOrderEnum::ORDER_ASC;

    public static $choices = [
        'Název (a-z)' => OrganizationSortByEnum::BY_NAME . '-' . SortOrderEnum::ORDER_ASC,
        'Název (z-a)' => OrganizationSortByEnum::BY_NAME . '-' . SortOrderEnum::ORDER_DESC,
        'Datum (nejnovější)' => OrganizationSortByEnum::BY_DATE . '-' . SortOrderEnum::ORDER_DESC,
        'Datum (nejstarší)' => OrganizationSortByEnum::BY_DATE . '-' . SortOrderEnum::ORDER_ASC,
    ];

    private static $propertyMap = [
        OrganizationSortByEnum::BY_NAME => 'organization.name',
        OrganizationSortByEnum::BY_DATE => 'organization.createdAt',
    ];

    public function create(Request $request): self
    {
        $sorting = new static();

        $sorting->setSortBy($this->extractSortBy($request));
        $sorting->setSortOrder($this->extractSortOrder($request));

        return $sorting;
    }

    public function apply(QueryBuilder $builder): void
    {
        if (!array_key_exists($this->getSortBy(), self::$propertyMap)) {
            throw new \RuntimeException(sprintf(
                'SortBy `%s` is not implemented in %s.',
                $this->getSortBy(),
                __METHOD__
            ));
        }

        $builder
            ->orderBy(
                self::$propertyMap[$this->getSortBy()],
                $this->getSortOrder()
            );
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
        return (string) $request->get('sort', OrganizationSortByEnum::BY_NAME .'-'. SortOrderEnum::ORDER_ASC);
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
}
