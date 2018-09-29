<?php declare(strict_types=1);

namespace App\Model\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class PointType extends Type
{
    public function getName(): string
    {
        return 'point';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'POINT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Point
    {
        [$longitude, $latitude] = sscanf($value, 'POINT(%f %f)');

        return new Point($latitude, $longitude);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Point) {
            $value = sprintf('POINT(%F %F)', $value->getLongitude(), $value->getLatitude());
        }

        return $value;
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        return sprintf('AsText(%s)', $sqlExpr);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return sprintf('PointFromText(%s)', $sqlExpr);
    }
}
