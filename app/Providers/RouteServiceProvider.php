<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('api.auth')
                ->prefix('scooter')
                ->group(base_path('routes/scooter.php'));

            Route::middleware('api.auth')
                ->prefix('mobile')
                ->group(base_path('routes/mobile.php'));
        });
    }
}
