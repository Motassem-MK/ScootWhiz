<?php

declare(strict_types=1);

namespace App\Trip\Repository\Eloquent\Builder;

use App\Trip\Model\Trip;
use App\Trip\Repository\Eloquent\Model\Trip as EloquentTrip;

class TripToEloquentModelBuilder
{
    public function build(Trip $trip, EloquentTrip $eloquentTrip): void
    {
        if ($trip->getUuid()) {
            $eloquentTrip->uuid = $trip->getUuid();
        }
        $eloquentTrip->scooter_uuid = $trip->getScooter()->getUuid();
        $eloquentTrip->client_uuid = $trip->getClient()->getUuid();
        $eloquentTrip->started_at = $trip->getStartTime();
        $eloquentTrip->ended_at = $trip->getEndTime();
    }
}
