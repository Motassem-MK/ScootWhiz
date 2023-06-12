<?php

declare(strict_types=1);

namespace App\Scooter\Repository\Eloquent\Builder;

use App\Scooter\Model\Scooter;
use App\Scooter\Repository\Eloquent\Model\Scooter as EloquentScooter;

class ScooterToEloquentModelBuilder
{
    public function build(Scooter $scooter, EloquentScooter $eloquentScooter): void
    {
        $eloquentScooter->uuid = $scooter->getUuid();
        $eloquentScooter->state = $scooter->getState();
    }
}
