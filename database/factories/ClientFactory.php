<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Client\Repository\Eloquent\Model\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
        ];
    }
}
