<?php

declare(strict_types=1);

namespace App\Providers\Scooter;

use App\Scooter\Repository\Eloquent\ScooterRepository as EloquentScooterRepository;
use App\Scooter\Repository\ScooterRepository;
use Illuminate\Support\ServiceProvider;

class ScooterRepositoryProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(ScooterRepository::class, EloquentScooterRepository::class);
    }
}
