<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Trip\Repository\Eloquent\Model\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trip>
 */
class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'started_at' => $this->faker->dateTime,
            'ended_at' => null,
        ];
    }

    public function ended(): static
    {
        return $this->state(fn(array $attributes) => [
            'ended_at' => $this->faker->dateTime,
        ]);
    }

    public function forScooter(string $uuid): static
    {
        return $this->state(fn(array $attributes): array => [
            'scooter_uuid' => $uuid,
        ]);
    }

    public function forClient(string $uuid): static
    {
        return $this->state(fn(array $attributes): array => [
            'client_uuid' => $uuid,
        ]);
    }
}
