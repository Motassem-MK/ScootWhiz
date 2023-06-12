<?php

declare(strict_types=1);

namespace App\Location\Dto;

use Illuminate\Contracts\Support\Arrayable;

readonly class Coordinates implements Arrayable
{
    public function __construct(
        public Coordinate $latitude,
        public Coordinate $longitude,
    ) {
    }

    public static function fromArray(array $parameters): self
    {
        return new self(
            new Coordinate($parameters['latitude']),
            new Coordinate($parameters['longitude']),
        );
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude->coordinate,
            'longitude' => $this->longitude->coordinate,
        ];
    }
}
