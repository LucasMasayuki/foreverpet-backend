<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccineBrand extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
