<?php

declare(strict_types=1);

namespace App\Client\Repository\Eloquent\Model;

use App\Traits\UsesUuid;
use App\Trip\Repository\Eloquent\Model\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $uuid
 */
class Client extends Model
{
    use UsesUuid;
    use HasFactory;

    protected $table = 'clients';

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
