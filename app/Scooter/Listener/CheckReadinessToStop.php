<?php

declare(strict_types=1);

namespace App\Scooter\Listener;

use App\Scooter\State\Validator\StateChangeValidationManager;
use App\Trip\Event\TripEnding;

readonly class CheckReadinessToStop
{
    public function __construct(private StateChangeValidationManager $validationManager)
    {
    }

    public function handle(TripEnding $event): void
    {
        $this->validationManager->check($event->getTrip()->getScooter());
    }
}
