<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Location\Dto\Coordinates;
use App\Location\Dto\RectangleCoordinates;
use App\Location\Resolver\BoundingCoordinatesResolver;
use App\ScooterLocation\Repository\Eloquent\Model\ScooterLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScooterLocation>
 */
class ScooterLocationFactory extends Factory
{
    protected $model = ScooterLocation::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->latitude(),
            'received_at' => $this->faker->dateTime,
            'scooter_uuid' => (new ScooterFactory())->create()->uuid,
        ];
    }

    public function forScooter(string $uuid): static
    {
        return $this->state(fn(array $attributes): array => [
            'scooter_uuid' => $uuid,
        ]);
    }

    public function forTrip(string $uuid): static
    {
        return $this->state(fn(array $attributes): array => [
            'trip_uuid' => $uuid,
        ]);
    }


}
