<?php

declare(strict_types=1);

namespace App\Http\Request\Scooter\Trip;

use App\Location\Dto\Coordinate;
use App\Location\Dto\Coordinates;
use App\ScooterLocation\Rule\ValidLatitude;
use App\ScooterLocation\Rule\ValidLongitude;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'scooter_uuid' => ['required', 'UUID'],
            'lat' => ['required', new ValidLatitude()],
            'long' => ['required', new ValidLongitude()],
        ];
    }

    public function getScooterUuid(): string
    {
        return $this->get('scooter_uuid');
    }

    public function getUpdatedCoordinates(): Coordinates
    {
        return new Coordinates(
            new Coordinate(round((float)$this->get('lat'), 8)),
            new Coordinate(round((float)$this->get('long'), 8)),
        );
    }
}
