<?php declare(strict_types = 1);

namespace App\Model\Enum;

use Consistence\Enum\Enum;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
final class OrganizationSortByEnum extends Enum
{
    public const BY_NAME = 'name';
    public const BY_DATE = 'date';
}
