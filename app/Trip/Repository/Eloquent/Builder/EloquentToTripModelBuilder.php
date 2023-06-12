<?php

declare(strict_types=1);

namespace App\Trip\Repository\Eloquent\Builder;

use App\Client\Repository\Eloquent\Builder\EloquentToClientModelBuilder;
use App\Scooter\Repository\Eloquent\Builder\EloquentToScooterModelBuilder;
use App\Trip\Model\Trip;
use App\Trip\Repository\Eloquent\Model\Trip as EloquentTrip;

readonly class EloquentToTripModelBuilder
{
    public function __construct(
        private EloquentToScooterModelBuilder $scooterBuilder,
        private EloquentToClientModelBuilder $clientBuilder,
    ) {
    }

    public function build(EloquentTrip $trip): Trip
    {
        return new Trip(
            $trip->uuid,
            $this->scooterBuilder->build($trip->scooter),
            $this->clientBuilder->build($trip->client),
            $trip->started_at,
            $trip->ended_at,
        );
    }
}
