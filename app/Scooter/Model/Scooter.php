<?php

declare(strict_types=1);

namespace App\Scooter\Model;

use App\Location\Dto\Coordinate;
use App\Location\Dto\Coordinates;
use App\Scooter\State\Enum\State;
use Illuminate\Contracts\Support\Arrayable;

class Scooter implements Arrayable
{
    public function __construct(
        private string $uuid,
        private State $state,
        private Coordinate $latitude,
        private Coordinate $longitude,
    ) {
    }

    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['uuid'],
            $parameters['state'],
            $parameters['latitude'],
            $parameters['longitude'],
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function updateState(State $newState): void
    {
        $this->state = $newState;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getCoordinates(): ?Coordinates
    {
        return new Coordinates($this->latitude, $this->longitude);
    }

    public function setCoordinates(Coordinates $coordinates): void
    {
        $this->latitude = $coordinates->latitude;
        $this->longitude = $coordinates->longitude;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->getUuid(),
            'state' => $this->getState()->value,
            'coordinates' => $this->getCoordinates()->toArray(),
        ];
    }
}
