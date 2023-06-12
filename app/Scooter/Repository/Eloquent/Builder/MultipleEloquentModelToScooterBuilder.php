<?php

declare(strict_types=1);

namespace App\Scooter\Repository\Eloquent\Builder;

use App\Scooter\Model\Scooter;
use App\Scooter\Repository\Eloquent\Model\Scooter as EloquentScooter;

readonly class MultipleEloquentModelToScooterBuilder
{
    public function __construct(private EloquentToScooterModelBuilder $builder)
    {
    }

    /**
     * @param EloquentScooter[] $eloquentScooters
     * @return Scooter[]
     */
    public function build(array $eloquentScooters): array
    {
        $scooters = [];
        foreach ($eloquentScooters as $eloquentScooter) {
            $scooters[] = $this->builder->build($eloquentScooter);
        }

        return $scooters;
    }
}
