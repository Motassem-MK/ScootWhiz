<?php

declare(strict_types=1);

namespace App\Scooter\Repository\Eloquent;

use App\Location\Dto\Coordinates;
use App\Location\Resolver\BoundingCoordinatesResolver;
use App\Scooter\Exception\ScooterNotFound;
use App\Scooter\Model\Scooter;
use App\Scooter\Repository\Eloquent\Builder\EloquentToScooterModelBuilder;
use App\Scooter\Repository\Eloquent\Builder\MultipleEloquentModelToScooterBuilder;
use App\Scooter\Repository\Eloquent\Builder\ScooterToEloquentModelBuilder;
use App\Scooter\Repository\Eloquent\Model\Scooter as EloquentScooter;
use App\Scooter\Repository\ScooterRepository as ScooterRepositoryInterface;
use App\Scooter\State\Enum\State;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

readonly class ScooterRepository implements ScooterRepositoryInterface
{
    public function __construct(
        private ScooterToEloquentModelBuilder $eloquentModelBuilder,
        private EloquentToScooterModelBuilder $scooterModelBuilder,
        private MultipleEloquentModelToScooterBuilder $multipleScooterModelBuilder,
        private BoundingCoordinatesResolver $boundsResolver,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findByUuid(string $uuid): Scooter
    {
        $eloquentScooter = $this->findEloquentModelByUuid($uuid);

        return $this->scooterModelBuilder->build($eloquentScooter);
    }

    public function findManyByUuid(array $uuids): array
    {
        $eloquentScooters = EloquentScooter::query()->whereIn('uuid', $uuids)->get()->all();

        return $this->multipleScooterModelBuilder->build($eloquentScooters);
    }

    /**
     * @inheritDoc
     */
    public function findByUuidAndLock(string $uuid): Scooter
    {
        $eloquentScooter = $this->findEloquentModelByUuid($uuid);
        $eloquentScooter->lockForUpdate();

        return $this->scooterModelBuilder->build($eloquentScooter);
    }

    /**
     * @inheritDoc
     */
    public function searchWithinCoordinates(Coordinates $firstPoint, Coordinates $secondPoint): array
    {
        /** @var EloquentScooter[] $eloquentScooters */
        $eloquentScooters = $this->buildSearchWithinCoordinatesQuery($firstPoint, $secondPoint)->get()->all();

        return $this->multipleScooterModelBuilder->build($eloquentScooters);
    }

    /**
     * @inheritDoc
     */
    public function searchByStateWithinCoordinates(
        Coordinates $firstPoint,
        Coordinates $secondPoint,
        State $state,
    ): array {
        /** @var EloquentScooter[] $eloquentScooters */
        $eloquentScooters = $this->buildSearchWithinCoordinatesQuery($firstPoint, $secondPoint)
            ->where('state', '=', $state->value)
            ->get()
            ->all();

        return $this->multipleScooterModelBuilder->build($eloquentScooters);
    }

    public function update(Scooter $scooter): void
    {
        $eloquentScooter = $this->findEloquentModelByUuid($scooter->getUuid());
        $this->eloquentModelBuilder->build($scooter, $eloquentScooter);
        $eloquentScooter->save();
    }

    private function findEloquentModelByUuid(string $uuid): EloquentScooter|Builder
    {
        return EloquentScooter::query()->findOr($uuid, function () {
            throw new ScooterNotFound();
        });
    }

    private function buildSearchWithinCoordinatesQuery(
        Coordinates $firstPoint,
        Coordinates $secondPoint,
    ): EloquentBuilder {
        $bounds = $this->boundsResolver->resolve($firstPoint, $secondPoint);

        return EloquentScooter::query()
            ->whereBetween('latitude', [$bounds->minLatitude, $bounds->maxLatitude])
            ->whereBetween('longitude', [$bounds->minLongitude, $bounds->maxLongitude]);
    }

    public function deleteManyByUuid(array $uuids): void
    {
        EloquentScooter::query()->whereIn('uuid', $uuids)->delete();
    }
}
