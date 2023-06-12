<?php

declare(strict_types=1);

namespace App\Scooter\Listener;

use App\Scooter\Repository\ScooterRepository;
use App\Scooter\State\Enum\State;
use App\Trip\Event\HasScooter;

readonly class MarkScooterAsAvailable
{
    public function __construct(private ScooterRepository $repository)
    {
    }

    public function handle(HasScooter $event): void
    {
        $event->getScooter()->updateState(State::AVAILABLE);

        $this->repository->update($event->getScooter());
    }
}
