<?php

declare(strict_types=1);

namespace App\Http\Request\Mobile;

use App\Location\Dto\Coordinate;
use App\Location\Dto\Coordinates;
use App\Scooter\State\Enum\State;
use App\Scooter\State\Enum\State as ScooterState;
use App\ScooterLocation\Rule\ValidLatitude;
use App\ScooterLocation\Rule\ValidLongitude;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ListScootersRequest extends FormRequest
{
    /**
     * @TODO add validation for maximum surface area
     */
    public function rules(): array
    {
        return [
            'lat1' => ['required', new ValidLatitude()],
            'long1' => ['required', new ValidLongitude()],
            'lat2' => ['required', new ValidLatitude()],
            'long2' => ['required', new ValidLongitude()],
            'state' => ['sometimes', new Enum(ScooterState::class)]
        ];
    }

    public function getFirstPoint(): Coordinates
    {
        return new Coordinates(
            new Coordinate(round((float)$this->get('lat1'), 8)),
            new Coordinate(round((float)$this->get('long1'), 8)),
        );
    }

    public function getSecondPoint(): Coordinates
    {
        return new Coordinates(
            new Coordinate(round((float)$this->get('lat2'), 8)),
            new Coordinate(round((float)$this->get('long2'), 8)),
        );
    }

    public function getState(): ?State
    {
        return $this->has('state') ? ScooterState::from($this->get('state')) : null;
    }
}
