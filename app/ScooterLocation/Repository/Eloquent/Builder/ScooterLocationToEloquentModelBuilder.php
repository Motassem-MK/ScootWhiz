<?php

declare(strict_types=1);

namespace App\ScooterLocation\Repository\Eloquent\Builder;

use App\ScooterLocation\Model\ScooterLocation;
use App\ScooterLocation\Repository\Eloquent\Model\ScooterLocation as EloquentScooterLocation;

class ScooterLocationToEloquentModelBuilder
{
    public function build(ScooterLocation $scooterLocation): EloquentScooterLocation
    {
        return new EloquentScooterLocation([
            'uuid' => $scooterLocation->getUuid(),
            'scooter_uuid' => $scooterLocation->getTrip()->getScooter()->getUuid(),
            'trip_uuid' => $scooterLocation->getTrip()->getUuid(),
            'latitude' => $scooterLocation->getCoordinates()->latitude->coordinate,
            'longitude' => $scooterLocation->getCoordinates()->longitude->coordinate,
            'received_at' => $scooterLocation->getReceivedAt(),
        ]);
    }
}
