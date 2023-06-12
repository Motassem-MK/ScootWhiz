<?php

declare(strict_types=1);

namespace App\Scooter\State\Validator;

use App\Scooter\Model\Scooter;

readonly class StateChangeValidationManager
{
    /**
     * @param StateChangeValidator[] $validators
     */
    public function __construct(private array $validators)
    {
    }

    public function check(Scooter $scooter): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($scooter);
        }
    }
}
