<?php declare(strict_types = 1);

namespace App\Model\Doctrine;

/**
 * Class representing binary (GPS) POINT SQL datatype.
 */
class Point
{
    /** @var float|null */
    private $latitude;

    /** @var float|null */
    private $longitude;

    public function __construct(?float $latitude = null, ?float $longitude = null)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
