<?php

declare(strict_types=1);

namespace App\Location\Dto;

use JsonSerializable;

readonly class Coordinate implements JsonSerializable
{
    public function __construct(public float $coordinate)
    {
    }

    public function jsonSerialize(): float
    {
        return $this->coordinate;
    }

    public function __toString(): string
    {
        return (string) $this->coordinate;
    }
}
