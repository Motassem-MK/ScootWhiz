<?php

declare(strict_types=1);

namespace Tests\Unit\Location\Resolver;

use App\Location\Dto\Coordinate;
use App\Location\Dto\Coordinates;
use App\Location\Dto\LocationBounds;
use App\Location\Resolver\BoundingCoordinatesResolver;
use PHPUnit\Framework\TestCase;

class BoundingCoordinatesResolverTest extends TestCase
{
    public function testResolveReturnsExpectedLocationBounds(): void
    {
        $resolver = new BoundingCoordinatesResolver();
        $firstPoint = new Coordinates(new Coordinate(40.748817), new Coordinate(-73.985428));
        $secondPoint = new Coordinates(new Coordinate(37.774929), new Coordinate(-122.419416));
        $expectedBounds = new LocationBounds(
            new Coordinate(37.774929),
            new Coordinate(-122.419416),
            new Coordinate(40.748817),
            new Coordinate(-73.985428),
        );

        $actualBounds = $resolver->resolve($firstPoint, $secondPoint);

        $this->assertEquals($expectedBounds, $actualBounds);
    }

    public function testResolveReturnsExpectedLocationBoundsWhenCoordinatesAreEqual(): void
    {
        $resolver = new BoundingCoordinatesResolver();
        $point = new Coordinates(new Coordinate(40.748817), new Coordinate(-73.985428));
        $expectedBounds = new LocationBounds(
            new Coordinate(40.748817),
            new Coordinate(-73.985428),
            new Coordinate(40.748817),
            new Coordinate(-73.985428),
        );

        $actualBounds = $resolver->resolve($point, $point);

        $this->assertEquals($expectedBounds, $actualBounds);
    }
}
