<?php

declare(strict_types=1);

namespace App\Trip\Event;

use App\Location\Dto\Coordinates;
use App\Trip\Model\Trip;
use Carbon\Carbon;

interface UpdatesScooterLocation
{
    public function getTrip(): Trip;

    public function getCoordinates(): Coordinates;

    public function getTimestamp(): Carbon;
}
