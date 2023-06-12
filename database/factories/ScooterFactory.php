<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Location\Dto\Coordinate;
use App\Location\Dto\RectangleCoordinates;
use App\Location\Resolver\BoundingCoordinatesResolver;
use App\Scooter\Repository\Eloquent\Model\Scooter;
use App\Scooter\State\Enum\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Scooter>
 */
class ScooterFactory extends Factory
{
    protected $model = Scooter::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'state' => $this->faker->randomElement(State::cases()),
            'latitude' => new Coordinate($this->faker->latitude()),
            'longitude' => new Coordinate($this->faker->latitude()),
        ];
    }

    public function available(): static
    {
        return $this->state(fn() => ['state' => State::AVAILABLE]);
    }

    public function occupied(): static
    {
        return $this->state(fn() => ['state' => State::OCCUPIED]);
    }

    public function insideRectangle(RectangleCoordinates $rectangle): static
    {
        /** @var BoundingCoordinatesResolver $boundsResolver */
        $boundsResolver = app()->make(BoundingCoordinatesResolver::class);
        $bounds = $boundsResolver->resolve($rectangle->point1, $rectangle->point2);

        return $this->state(fn(array $attributes): array => [
            'latitude' => new Coordinate(
                $this->faker->latitude($bounds->minLatitude->coordinate, $bounds->maxLatitude->coordinate),
            ),
            'longitude' => new Coordinate(
                $this->faker->longitude(
                    $bounds->minLongitude->coordinate,
                    $bounds->maxLongitude->coordinate,
                ),
            ),
        ]);
    }

    public function outsideRectangle(RectangleCoordinates $rectangle): static
    {
        /** @var BoundingCoordinatesResolver $boundsResolver */
        $boundsResolver = app()->make(BoundingCoordinatesResolver::class);
        $bounds = $boundsResolver->resolve($rectangle->point1, $rectangle->point2);

        return $this->state(fn(array $attributes): array => [
            'latitude' => new Coordinate($this->faker->latitude()),
            'longitude' => new Coordinate(
                $this->faker->longitude(
                    -$bounds->maxLongitude->coordinate,
                    -$bounds->minLongitude->coordinate,
                ),
            ),
        ]);
    }
}
