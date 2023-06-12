<?php

declare(strict_types=1);

namespace App\Location\Dto;

readonly class RectangleCoordinates
{
    public function __construct(public Coordinates $point1, public Coordinates $point2)
    {
    }
}
