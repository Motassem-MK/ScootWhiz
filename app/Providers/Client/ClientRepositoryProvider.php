<?php

declare(strict_types=1);

namespace App\Providers\Client;

use App\Client\Repository\ClientRepository;
use App\Client\Repository\Eloquent\ClientRepository as EloquentClientRepository;
use Illuminate\Support\ServiceProvider;

class ClientRepositoryProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(ClientRepository::class, EloquentClientRepository::class);
    }
}
