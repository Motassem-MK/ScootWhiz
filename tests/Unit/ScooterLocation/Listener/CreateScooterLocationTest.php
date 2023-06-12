<?php

declare(strict_types=1);

namespace Tests\Unit\ScooterLocation\Listener;

use App\Location\Dto\Coordinate;
use App\Location\Dto\Coordinates;
use App\ScooterLocation\Listener\CreateScooterLocation;
use App\ScooterLocation\Model\ScooterLocation;
use App\ScooterLocation\Repository\ScooterLocationRepository;
use App\Trip\Event\UpdatesScooterLocation;
use App\Trip\Model\Trip;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CreateScooterLocationTest extends TestCase
{
    public function testShouldCreateScooterLocation(): void
    {
        $repository = $this->createMock(ScooterLocationRepository::class);
        $event = $this->createMock(UpdatesScooterLocation::class);
        $trip = $this->createMock(Trip::class);
        $coordinates = new Coordinates(new Coordinate(0), new Coordinate(0));
        $timestamp = Carbon::create(2023, 5, 4);

        $event->method('getTrip')->willReturn($trip);
        $event->method('getCoordinates')->willReturn($coordinates);
        $event->method('getTimestamp')->willReturn($timestamp);

        $repository->expects(self::once())
            ->method('create')
            ->with(
                self::callback(
                    function (ScooterLocation $scooterLocation) use ($trip, $coordinates, $timestamp): bool {
                        if (!$scooterLocation->getTrip() === $trip) {
                            return false;
                        }

                        if (!$scooterLocation->getCoordinates() === $coordinates) {
                            return false;
                        }

                        if (!$scooterLocation->getReceivedAt() === $timestamp) {
                            return false;
                        }

                        return true;
                    }
                ),
            );

        (new CreateScooterLocation($repository))->handle($event);
    }
}
