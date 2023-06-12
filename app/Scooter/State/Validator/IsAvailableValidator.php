<?php

declare(strict_types=1);

namespace App\Scooter\State\Validator;

use App\Scooter\Model\Scooter;
use App\Scooter\State\Enum\State;
use App\Scooter\State\Validator\Exception\ScooterStateChangeException;

class IsAvailableValidator implements StateChangeValidator
{
    /**
     * @inheritDoc
     */
    public function validate(Scooter $scooter): void
    {
        if ($scooter->getState() !== State::AVAILABLE) {
            throw new ScooterStateChangeException('Scooter already started');
        }
    }
}
