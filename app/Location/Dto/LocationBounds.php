<?php

declare(strict_types=1);

namespace App\Location\Dto;

readonly class LocationBounds
{
    public function __construct(
        public Coordinate $minLatitude,
        public Coordinate $minLongitude,
        public Coordinate $maxLatitude,
        public Coordinate $maxLongitude,
    ) {
    }
}
