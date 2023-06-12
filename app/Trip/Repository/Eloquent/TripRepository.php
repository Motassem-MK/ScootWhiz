<?php

declare(strict_types=1);

namespace App\Trip\Repository\Eloquent;

use App\Trip\Exception\OngoingTripNotFound;
use App\Trip\Exception\TripNotFound;
use App\Trip\Model\Trip;
use App\Trip\Repository\Eloquent\Builder\EloquentToTripModelBuilder;
use App\Trip\Repository\Eloquent\Builder\TripToEloquentModelBuilder;
use App\Trip\Repository\Eloquent\Model\Trip as EloquentTrip;
use App\Trip\Repository\TripRepository as TripRepositoryInterface;

readonly class TripRepository implements TripRepositoryInterface
{
    public function __construct(
        private EloquentToTripModelBuilder $modelBuilder,
        private TripToEloquentModelBuilder $eloquentModelBuilder,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findOngoingForScooter(string $scooterUuid): Trip
    {
        /** @var ?EloquentTrip $eloquentTrip */
        $eloquentTrip = EloquentTrip::query()
            ->where('scooter_uuid', $scooterUuid)
            ->whereNull('ended_at')
            ->first();

        if (!$eloquentTrip) {
            throw new OngoingTripNotFound();
        }

        return $this->modelBuilder->build($eloquentTrip);
    }

    public function create(Trip $trip): void
    {
        $eloquentTrip = new EloquentTrip();
        $this->eloquentModelBuilder->build($trip, $eloquentTrip);
        $eloquentTrip->save();
        $trip->setUuid($eloquentTrip->uuid);
    }

    public function update(Trip $trip): void
    {
        $eloquentTrip = $this->findEloquentModelByUuid($trip->getUuid());
        $this->eloquentModelBuilder->build($trip, $eloquentTrip);
        $eloquentTrip->save();
    }

    private function findEloquentModelByUuid(string $uuid): EloquentTrip
    {
        return EloquentTrip::query()->findOr($uuid, 'uuid', fn() => throw new TripNotFound());
    }
}
