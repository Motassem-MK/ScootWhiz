<?php

declare(strict_types=1);

namespace App\Scooter\Listener;

use App\Scooter\Repository\ScooterRepository;
use App\Scooter\State\Enum\State;
use App\Trip\Event\TripStarting;

readonly class MarkScooterAsOccupied
{
    public function __construct(private ScooterRepository $repository)
    {
    }

    public function handle(TripStarting $event): void
    {
        $event->getScooter()->updateState(State::OCCUPIED);

        $this->repository->update($event->getScooter());
    }
}
