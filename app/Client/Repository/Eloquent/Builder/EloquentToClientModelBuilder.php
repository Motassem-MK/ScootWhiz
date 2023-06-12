<?php

declare(strict_types=1);

namespace App\Client\Repository\Eloquent\Builder;

use App\Client\Model\Client;
use App\Client\Repository\Eloquent\Model\Client as EloquentClient;

class EloquentToClientModelBuilder
{
    public function build(EloquentClient $client): Client
    {
        return new Client(
            $client->uuid,
        );
    }
}
