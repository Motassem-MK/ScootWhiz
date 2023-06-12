<?php

declare(strict_types=1);

namespace App\Client\Repository\Eloquent;

use App\Client\Exception\ClientNotFound;
use App\Client\Model\Client;
use App\Client\Repository\ClientRepository as ClientRepositoryInterface;
use App\Client\Repository\Eloquent\Builder\EloquentToClientModelBuilder;
use App\Client\Repository\Eloquent\Model\Client as EloquentClient;

readonly class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(private EloquentToClientModelBuilder $builder)
    {
    }

    /**
     * @inheritDoc
     */
    public function findByUuid(string $uuid): Client
    {
        $eloquentClient = EloquentClient::query()->findOr($uuid, 'uuid', fn() => throw new ClientNotFound());

        return $this->builder->build($eloquentClient);
    }

    /**
     * @inheritDoc
     */
    public function deleteManyByUuid(array $uuids): void
    {
        EloquentClient::query()->whereIn('uuid', $uuids)->delete();
    }
}
