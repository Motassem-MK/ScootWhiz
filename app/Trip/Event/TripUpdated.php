<?php

declare(strict_types=1);

namespace App\Trip\Event;

use App\Location\Dto\Coordinates;
use App\Trip\Model\Trip;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;

class TripUpdated implements UpdatesScooterLocation
{
    use Dispatchable;

    public function __construct(
        private readonly Trip $trip,
        private readonly Coordinates $coordinates,
        private readonly Carbon $timestamp,
    ) {
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }
}
