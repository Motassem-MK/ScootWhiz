<?php

declare(strict_types=1);

namespace App\ScooterLocation\Model;

use App\Location\Dto\Coordinates;
use App\Trip\Model\Trip;
use Carbon\Carbon;

class ScooterLocation
{
    public function __construct(
        private ?string $uuid,
        private Trip $trip,
        private Coordinates $coordinates,
        private Carbon $receivedAt,
    ) {
    }

    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['uuid'],
            Trip::fromArray($parameters['trip']),
            Coordinates::fromArray($parameters['coordinates']),
            $parameters['receivedAt'],
        );
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function getReceivedAt(): Carbon
    {
        return $this->receivedAt;
    }
}
