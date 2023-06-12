<?php

declare(strict_types=1);

namespace App\Providers\Scooter;

use App\Scooter\Listener\CheckReadinessToStart;
use App\Scooter\Listener\CheckReadinessToStop;
use Illuminate\Support\ServiceProvider;

class ScooterListenersProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(CheckReadinessToStart::class, function () {
            return new CheckReadinessToStart(
                $this->app->make('ScooterStartValidators'),
            );
        });

        $this->app->bind(CheckReadinessToStop::class, function () {
            return new CheckReadinessToStop(
                $this->app->make('ScooterStopValidators'),
            );
        });
    }
}
