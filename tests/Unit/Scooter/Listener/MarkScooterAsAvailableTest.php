<?php

declare(strict_types=1);

namespace Tests\Unit\Scooter\Listener;

use App\Scooter\Listener\MarkScooterAsAvailable;
use App\Scooter\Model\Scooter;
use App\Scooter\Repository\ScooterRepository;
use App\Scooter\State\Enum\State;
use App\Trip\Event\HasScooter;
use PHPUnit\Framework\TestCase;

class MarkScooterAsAvailableTest extends TestCase
{
    public function testShouldMarkScooterAsAvailable(): void
    {
        $repository = $this->createMock(ScooterRepository::class);
        $event = $this->createMock(HasScooter::class);
        $scooter = $this->createMock(Scooter::class);

        $event->method('getScooter')->willReturn($scooter);

        $scooter->expects(self::once())->method('updateState')->with(State::AVAILABLE);
        $repository->expects(self::once())->method('update')->with($scooter);

        (new MarkScooterAsAvailable($repository))->handle($event);
    }
}
