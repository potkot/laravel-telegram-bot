<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaterBase extends Model
{
    protected $table = 'waters_bases';
    protected $guarded = ['id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_uuid', 'uuid');
    }

    public function volumes(): HasMany
    {
        return $this->hasMany(WaterVolume::class, 'water_base_uuid', 'uuid');
    }
}
