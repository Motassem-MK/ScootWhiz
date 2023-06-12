<?php

declare(strict_types=1);

namespace App\Scooter\Repository;

use App\Location\Dto\Coordinates;
use App\Scooter\Exception\ScooterNotFound;
use App\Scooter\Model\Scooter;
use App\Scooter\State\Enum\State;

interface ScooterRepository
{
    /**
     * @throws ScooterNotFound
     */
    public function findByUuid(string $uuid): Scooter;

    /**
     * @param string[] $uuids
     * @return Scooter[]
     * @throws ScooterNotFound
     */
    public function findManyByUuid(array $uuids): array;

    /**
     * @throws ScooterNotFound
     */
    public function findByUuidAndLock(string $uuid): Scooter;

    /**
     * @return Scooter[]
     */
    public function searchWithinCoordinates(Coordinates $firstPoint, Coordinates $secondPoint): array;

    /**
     * @return Scooter[]
     */
    public function searchByStateWithinCoordinates(
        Coordinates $firstPoint,
        Coordinates $secondPoint,
        State $state,
    ): array;

    public function update(Scooter $scooter): void;

    /**
     * @param string[] $uuids
     */
    public function deleteManyByUuid(array $uuids): void;
}
