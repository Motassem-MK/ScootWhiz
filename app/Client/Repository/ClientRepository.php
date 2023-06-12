<?php

declare(strict_types=1);

namespace App\Client\Repository;

use App\Client\Exception\ClientNotFound;
use App\Client\Model\Client;

interface ClientRepository
{
    /**
     * @throws ClientNotFound
     */
    public function findByUuid(string $uuid): Client;

    /**
     * @param string[] $uuids
     */
    public function deleteManyByUuid(array $uuids): void;
}
