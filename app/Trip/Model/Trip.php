<?php

declare(strict_types=1);

namespace App\Trip\Model;

use App\Client\Model\Client;
use App\Scooter\Model\Scooter;
use Carbon\Carbon;

class Trip
{
    public function __construct(
        private ?string $uuid,
        private readonly Scooter $scooter,
        private readonly Client $client,
        private readonly Carbon $startTime,
        private ?Carbon $endTime,
    ) {
    }

    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['uuid'],
            Scooter::fromArray($parameters['scooter']),
            Client::fromArray($parameters['client']),
            $parameters['startTime'],
            $parameters['endTime'],
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

    public function getScooter(): Scooter
    {
        return $this->scooter;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getStartTime(): Carbon
    {
        return $this->startTime;
    }

    public function setEndTime(Carbon $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getEndTime(): ?Carbon
    {
        return $this->endTime;
    }
}
