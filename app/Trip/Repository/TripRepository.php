<?php

declare(strict_types=1);

namespace App\Trip\Repository;

use App\Trip\Exception\OngoingTripNotFound;
use App\Trip\Exception\TripNotFound;
use App\Trip\Model\Trip;

interface TripRepository
{
    /**
     * @throws OngoingTripNotFound
     */
    public function findOngoingForScooter(string $scooterUuid): Trip;

    public function create(Trip $trip): void;

    /**
     * @throws TripNotFound
     */
    public function update(Trip $trip): void;
}
