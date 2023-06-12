<?php

declare(strict_types=1);

namespace App\ScooterLocation\Repository\Eloquent;

use App\ScooterLocation\Model\ScooterLocation;
use App\ScooterLocation\Repository\Eloquent\Builder\ScooterLocationToEloquentModelBuilder;
use App\ScooterLocation\Repository\ScooterLocationRepository as ScooterLocationRepositoryInterface;

readonly class ScooterLocationRepository implements ScooterLocationRepositoryInterface
{
    public function __construct(private ScooterLocationToEloquentModelBuilder $builder)
    {
    }

    public function create(ScooterLocation $scooterLocation): void
    {
        $eloquentScooterLocation = $this->builder->build($scooterLocation);
        $eloquentScooterLocation->save();
        $scooterLocation->setUuid($eloquentScooterLocation->uuid);
    }
}
