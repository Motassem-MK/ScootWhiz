<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Location\Dto\Coordinate;
use App\Location\Dto\Coordinates;
use App\Location\Dto\RectangleCoordinates;
use App\Scooter\State\Enum\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingScootersTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/mobile/scooters';

    public function testShouldListAllScootersWithinCoordinates(): void
    {
        $lat1 = 40.7128;
        $lat2 = 42.7128;
        $long1 = -72.0060;
        $long2 = -74.0060;

        $rectangle = new RectangleCoordinates(
            new Coordinates(new Coordinate($lat1), new Coordinate($long1)),
            new Coordinates(new Coordinate($lat2), new Coordinate($long2)),
        );

        $this->createScooterInsideCoordinates($rectangle, 5);
        $this->createScooterOutsideCoordinates($rectangle, 10);

        $this
            ->get(
                sprintf('%s?lat1=%f&long1=%f&lat2=%f&long2=%f', self::ENDPOINT, $lat1, $long1, $lat2, $long2),
                [
                    'Accept' => 'application/json',
                    'Authorization' => self::API_KEY,
                ]
            )
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function testShouldListAllAvailableScootersWithinCoordinates(): void
    {
        $lat1 = 40.7128;
        $lat2 = 42.7128;
        $long1 = -72.0060;
        $long2 = -74.0060;

        $rectangle = new RectangleCoordinates(
            new Coordinates(new Coordinate($lat1), new Coordinate($long1)),
            new Coordinates(new Coordinate($lat2), new Coordinate($long2)),
        );

        $this->createScooterInsideCoordinates($rectangle, 3, ['state' => State::AVAILABLE]);
        $this->createScooterInsideCoordinates($rectangle, 5, ['state' => State::OCCUPIED]);

        $this
            ->get(
                sprintf(
                    '%s?lat1=%f&long1=%f&lat2=%f&long2=%f&state=%s',
                    self::ENDPOINT,
                    $lat1,
                    $long1,
                    $lat2,
                    $long2,
                    State::AVAILABLE->value,
                ),
                [
                    'Accept' => 'application/json',
                    'Authorization' => self::API_KEY,
                ]
            )
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }
}
