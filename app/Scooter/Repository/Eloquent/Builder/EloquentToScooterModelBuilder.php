<?php

declare(strict_types=1);

namespace App\Scooter\Repository\Eloquent\Builder;

use App\Location\Dto\Coordinate;
use App\Scooter\Model\Scooter;
use App\Scooter\Repository\Eloquent\Model\Scooter as EloquentScooter;

class EloquentToScooterModelBuilder
{
    public function build(EloquentScooter $scooter): Scooter
    {
        return Scooter::fromArray([
            'uuid' => $scooter->uuid,
            'state' => $scooter->state,
            'latitude' => new Coordinate($scooter->latitude),
            'longitude' => new Coordinate($scooter->longitude),
        ]);
    }
}
