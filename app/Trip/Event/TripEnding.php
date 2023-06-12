<?php

declare(strict_types=1);

namespace App\Trip\Event;

use App\Location\Dto\Coordinates;
use App\Scooter\Model\Scooter;
use App\Trip\Model\Trip;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;

class TripEnding implements HasScooter
{
    use Dispatchable;

    public function __construct(
        private readonly Trip $trip,
        private readonly Coordinates $location,
        private readonly Carbon $timestamp,
    ) {
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function getLocation(): Coordinates
    {
        return $this->location;
    }

    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }

    public function getScooter(): Scooter
    {
        return $this->trip->getScooter();
    }
}
