<?php

declare(strict_types=1);

namespace App\Scooter\State\Validator;

use App\Scooter\Model\Scooter;
use App\Scooter\State\Validator\Exception\ScooterStateChangeException;

interface StateChangeValidator
{
    /**
     * @throws ScooterStateChangeException
     */
    public function validate(Scooter $scooter): void;
}
