<?php

declare(strict_types=1);

namespace Tests\Unit\Scooter\State\Validator;

use App\Scooter\Model\Scooter;
use App\Scooter\State\Validator\StateChangeValidationManager;
use App\Scooter\State\Validator\StateChangeValidator;
use PHPUnit\Framework\TestCase;

class StateChangeValidationManagerTest extends TestCase
{
    public function testShouldValidateWithAllProvidedValidators(): void
    {
        $validator1 = $this->createMock(StateChangeValidator::class);
        $validator2 = $this->createMock(StateChangeValidator::class);

        $scooter = $this->createMock(Scooter::class);

        $validator1->expects(self::once())->method('validate')->with($scooter);
        $validator2->expects(self::once())->method('validate')->with($scooter);

        (new StateChangeValidationManager([$validator1, $validator2]))->check($scooter);
    }
}
