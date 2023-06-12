<?php

declare(strict_types=1);

namespace Tests\Unit\Scooter\State\Validator;

use App\Location\Dto\Coordinate;
use App\Scooter\Model\Scooter;
use App\Scooter\State\Enum\State;
use App\Scooter\State\Validator\Exception\ScooterStateChangeException;
use App\Scooter\State\Validator\IsAvailableValidator;
use PHPUnit\Framework\TestCase;

class IsAvailableValidatorTest extends TestCase
{
    public function testShouldAllowAvailableScooter(): void
    {
        $scooter = new Scooter('x', State::AVAILABLE, new Coordinate(1), new Coordinate(1));

        (new IsAvailableValidator())->validate($scooter);

        $this->assertTrue(true);
    }

    public function testShouldNotAllowOccupiedScooter(): void
    {
        $scooter = new Scooter('x', State::OCCUPIED, new Coordinate(1), new Coordinate(1));

        $this->expectException(ScooterStateChangeException::class);

        (new IsAvailableValidator())->validate($scooter);
    }
}
