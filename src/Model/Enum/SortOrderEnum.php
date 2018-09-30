<?php declare(strict_types = 1);

namespace App\Model\Enum;

use Consistence\Enum\Enum;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
final class SortOrderEnum extends Enum
{
    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';
}
