<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Scooter\Model\Scooter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Scooter $resource
 */
class ScooterResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
