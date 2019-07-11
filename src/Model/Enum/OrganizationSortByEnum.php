<?php declare(strict_types = 1);

namespace App\Model\Enum;

use Consistence\Enum\Enum;

final class OrganizationSortByEnum extends Enum
{
    public const BY_NAME = 'name';
    public const BY_DATE = 'date';
}
