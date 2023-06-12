<?php

namespace App\Providers;

use App\Demo\Command\SpawnDemoClients;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SpawnDemoClients::class,
            ]);
        }
    }
}
