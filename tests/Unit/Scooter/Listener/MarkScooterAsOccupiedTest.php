<?php

declare(strict_types=1);

namespace Tests\Unit\Scooter\Listener;

use App\Scooter\Listener\MarkScooterAsOccupied;
use App\Scooter\Model\Scooter;
use App\Scooter\Repository\ScooterRepository;
use App\Scooter\State\Enum\State;
use App\Trip\Event\TripStarting;
use PHPUnit\Framework\TestCase;

class MarkScooterAsOccupiedTest extends TestCase
{
    public function testShouldMarkScooterAsAvailable(): void
    {
        $repository = $this->createMock(ScooterRepository::class);
        $event = $this->createMock(TripStarting::class);
        $scooter = $this->createMock(Scooter::class);

        $event->method('getScooter')->willReturn($scooter);

        $scooter->expects(self::once())->method('updateState')->with(State::OCCUPIED);
        $repository->expects(self::once())->method('update')->with($scooter);

        (new MarkScooterAsOccupied($repository))->handle($event);
    }
}
