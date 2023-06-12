<?php

declare(strict_types=1);

namespace App\Trip\Event;

use App\Scooter\Model\Scooter;

interface HasScooter
{
    public function getScooter(): Scooter;
}
