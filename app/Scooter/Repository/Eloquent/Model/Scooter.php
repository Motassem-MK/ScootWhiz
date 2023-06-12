<?php

declare(strict_types=1);

namespace App\Scooter\Repository\Eloquent\Model;

use App\Scooter\State\Enum\State;
use App\ScooterLocation\Repository\Eloquent\Model\ScooterLocation;
use App\Traits\UsesUuid;
use App\Trip\Repository\Eloquent\Model\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $uuid
 * @property State $state
 * @property float $latitude
 * @property float $longitude
 */
class Scooter extends Model
{
    use UsesUuid;
    use HasFactory;

    protected $table = 'scooters';

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'state',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'state' => State::class,
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'scooter_uuid', 'uuid');
    }

    public function locationHistory(): HasMany
    {
        return $this->hasMany(ScooterLocation::class, 'scooter_uuid', 'uuid');
    }

    public function currentLocation(): HasOne
    {
        return $this->hasOne(ScooterLocation::class, 'scooter_uuid', 'uuid')
            ->latest('received_at');
    }
}
