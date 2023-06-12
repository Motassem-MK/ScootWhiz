<?php

declare(strict_types=1);

namespace App\Trip\Listener;

use App\Trip\Event\TripStarted;
use App\Trip\Event\TripStarting;
use App\Trip\Model\Trip;
use App\Trip\Repository\TripRepository;

readonly class StartTrip
{
    public function __construct(private TripRepository $repository)
    {
    }

    public function handle(TripStarting $event): void
    {
        $trip = new Trip(
            null,
            $event->getScooter(),
            $event->getClient(),
            $event->getTimestamp(),
            null,
        );

        $this->repository->create($trip);

        TripStarted::dispatch($trip, $event->getCoordinates(), $event->getTimestamp());
    }
}
