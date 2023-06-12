<?php

declare(strict_types=1);

namespace App\Scooter\State\Enum;

enum State: string
{
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';
}
