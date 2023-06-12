<?php

declare(strict_types=1);

namespace App\ScooterLocation\Repository;

use App\ScooterLocation\Model\ScooterLocation;

interface ScooterLocationRepository
{
    public function create(ScooterLocation $scooterLocation): void;
}
