<?php

declare(strict_types=1);

namespace App\ScooterLocation\Listener;

use App\ScooterLocation\Model\ScooterLocation;
use App\ScooterLocation\Repository\ScooterLocationRepository;
use App\Trip\Event\UpdatesScooterLocation;
use Illuminate\Contracts\Queue\ShouldQueue;

readonly class CreateScooterLocation implements ShouldQueue
{
    public function __construct(private ScooterLocationRepository $repository)
    {
    }

    public function handle(UpdatesScooterLocation $event): void
    {
        $scooterLocation = new ScooterLocation(
            null,
            $event->getTrip(),
            $event->getCoordinates(),
            $event->getTimestamp(),
        );

        $this->repository->create($scooterLocation);
    }
}
