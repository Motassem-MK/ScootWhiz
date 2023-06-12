<?php

declare(strict_types=1);

namespace App\Trip\Listener;

use App\Trip\Event\TripEnded;
use App\Trip\Event\TripEnding;
use App\Trip\Repository\TripRepository;

readonly class EndTrip
{
    public function __construct(private TripRepository $repository)
    {
    }

    public function handle(TripEnding $event): void
    {
        $event->getTrip()->setEndTime($event->getTimestamp());

        $this->repository->update($event->getTrip());

        TripEnded::dispatch($event->getTrip(), $event->getLocation(), $event->getTimestamp());
    }
}
