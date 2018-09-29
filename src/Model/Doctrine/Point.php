<?php declare(strict_types=1);

namespace App\Model\Doctrine;

/**
 * Class representing binary (GPS) POINT SQL datatype.
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Point
{
    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
