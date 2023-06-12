<?php

declare(strict_types=1);

namespace App\ScooterLocation\Repository\Eloquent\Model;

use App\Scooter\Repository\Eloquent\Model\Scooter;
use App\Traits\UsesUuid;
use App\Trip\Repository\Eloquent\Model\Trip;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property string $scooter_uuid
 * @property string $trip_uuid
 * @property float $latitude
 * @property float $longitude
 * @property Carbon $received_at
 * @property Scooter $scooter
 * @property Trip $trip
 */
class ScooterLocation extends Model
{
    use UsesUuid;
    use HasFactory;

    protected $table = 'scooter_locations';

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'scooter_uuid',
        'trip_uuid',
        'latitude',
        'longitude',
        'received_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'received_at' => 'datetime',
    ];

    public function scooter(): BelongsTo
    {
        return $this->belongsTo(Scooter::class, 'scooter_uuid', 'uuid');
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_uuid', 'uuid');
    }
}
