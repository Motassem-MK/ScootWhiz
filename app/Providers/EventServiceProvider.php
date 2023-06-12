<?php

declare(strict_types=1);

namespace App\Providers;

use App\Scooter\Listener\CheckReadinessToStart;
use App\Scooter\Listener\CheckReadinessToStop;
use App\Scooter\Listener\MarkScooterAsAvailable;
use App\Scooter\Listener\MarkScooterAsOccupied;
use App\ScooterLocation\Listener\CreateScooterLocation;
use App\Trip\Event\TripEnded;
use App\Trip\Event\TripEnding;
use App\Trip\Event\TripStarted;
use App\Trip\Event\TripStarting;
use App\Trip\Event\TripUpdated;
use App\Trip\Listener\EndTrip;
use App\Trip\Listener\StartTrip;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        TripStarting::class => [
            CheckReadinessToStart::class,
            MarkScooterAsOccupied::class,
            StartTrip::class,
        ],
        TripStarted::class => [
            CreateScooterLocation::class,
            // Tracking & other async processing can go here
        ],

        TripEnding::class => [
            CheckReadinessToStop::class,
            EndTrip::class,
            MarkScooterAsAvailable::class,
        ],
        TripEnded::class => [
            CreateScooterLocation::class,
        ],

        TripUpdated::class => [
            CreateScooterLocation::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
