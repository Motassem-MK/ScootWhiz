<?php

declare(strict_types=1);

namespace App\Scooter\Listener;

use App\Scooter\State\Validator\StateChangeValidationManager;
use App\Trip\Event\TripStarting;

readonly class CheckReadinessToStart
{
    public function __construct(private StateChangeValidationManager $validationManager)
    {
    }

    public function handle(TripStarting $event): void
    {
        $this->validationManager->check($event->getScooter());
    }
}
