<?php

declare(strict_types=1);

namespace App\Providers\Trip;

use App\Trip\Repository\Eloquent\TripRepository as EloquentTripRepository;
use App\Trip\Repository\TripRepository;
use Illuminate\Support\ServiceProvider;

class TripRepositoryProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(TripRepository::class, EloquentTripRepository::class);
    }
}
