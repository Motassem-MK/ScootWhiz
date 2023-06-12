<?php

namespace Tests;

use App\Location\Dto\RectangleCoordinates;
use Database\Factories\ScooterFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\CreatesApplication;
use Tests\Traits\RequiresAuthentication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    use RequiresAuthentication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTestKey();
    }

    protected function createScooterInsideCoordinates(
        RectangleCoordinates $rectangle,
        int $count,
        array $attributes = [],
    ): void {
        (new ScooterFactory())
            ->insideRectangle($rectangle)
            ->count($count)
            ->create($attributes);
    }

    protected function createScooterOutsideCoordinates(
        RectangleCoordinates $rectangle,
        int $count,
        array $attributes = [],
    ): void {
        (new ScooterFactory())
            ->outsideRectangle($rectangle)
            ->count($count)
            ->create($attributes);
    }
}
