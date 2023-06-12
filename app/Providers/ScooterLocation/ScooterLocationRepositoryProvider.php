<?php

declare(strict_types=1);

namespace App\Providers\ScooterLocation;

use App\ScooterLocation\Repository\Eloquent\ScooterLocationRepository as EloquentScooterLocationRepository;
use App\ScooterLocation\Repository\ScooterLocationRepository;
use Illuminate\Support\ServiceProvider;

class ScooterLocationRepositoryProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(ScooterLocationRepository::class, EloquentScooterLocationRepository::class);
    }
}
