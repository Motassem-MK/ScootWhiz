<?php

declare(strict_types=1);

namespace App\Location\Resolver;

use App\Location\Dto\Coordinates;
use App\Location\Dto\LocationBounds;

class BoundingCoordinatesResolver
{
    public function resolve(Coordinates $firstPoint, Coordinates $secondPoint): LocationBounds
    {
        return new LocationBounds(
            min($firstPoint->latitude, $secondPoint->latitude),
            min($firstPoint->longitude, $secondPoint->longitude),
            max($firstPoint->latitude, $secondPoint->latitude),
            max($firstPoint->longitude, $secondPoint->longitude),
        );
    }
}
