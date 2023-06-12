<?php

declare(strict_types=1);

namespace App\Trip\Repository\Eloquent\Model;

use App\Client\Repository\Eloquent\Model\Client;
use App\Scooter\Repository\Eloquent\Model\Scooter;
use App\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property string $scooter_uuid
 * @property string $client_uuid
 * @property Carbon $started_at
 * @property Carbon $ended_at
 */
class Trip extends Model
{
    use UsesUuid;
    use HasFactory;

    protected $table = 'trips';

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'scooter_uuid',
        'client_uuid',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function scooter(): BelongsTo
    {
        return $this->belongsTo(Scooter::class, 'scooter_uuid', 'uuid');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_uuid', 'uuid');
    }
}
