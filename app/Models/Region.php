<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $table = 'regions';
    protected $fillable = ['id'];

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class, 'region_id', 'id');
    }

    public function watersBases(): HasMany
    {
        return $this->hasMany(WaterBase::class, 'region_uuid', 'uuid');
    }
}
