<?php

declare(strict_types=1);

namespace App\Trip\Event;

use App\Client\Model\Client;
use App\Location\Dto\Coordinates;
use App\Scooter\Model\Scooter;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;

class TripStarting implements HasScooter
{
    use Dispatchable;

    public function __construct(
        private readonly Scooter $scooter,
        private readonly Client $client,
        private readonly Coordinates $location,
        private readonly Carbon $timestamp,
    ) {
    }

    public function getScooter(): Scooter
    {
        return $this->scooter;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->location;
    }

    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }
}
