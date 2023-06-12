<?php

declare(strict_types=1);

namespace App\Providers\Scooter;

use App\Scooter\State\Validator\IsAvailableValidator;
use App\Scooter\State\Validator\IsOccupiedValidator;
use App\Scooter\State\Validator\StateChangeValidationManager;
use Illuminate\Support\ServiceProvider;

class ScooterStateValidatorsProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind('ScooterStartValidators', function () {
            return new StateChangeValidationManager([
                $this->app->make(IsAvailableValidator::class),
                /**
                 * Validations for other criteria can go here, e.g:
                 *
                 * MinimumAllowedBatteryLevelValidator::class
                 * NoScheduledMaintenanceValidator::class
                 * NoCriticalComplaintsValidator::class
                 */
            ]);
        });

        $this->app->bind('ScooterStopValidators', function () {
            return new StateChangeValidationManager([
                $this->app->make(IsOccupiedValidator::class),
            ]);
        });
    }
}
