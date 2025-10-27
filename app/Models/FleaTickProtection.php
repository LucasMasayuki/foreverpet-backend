<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleaTickProtection extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'enabled',
        'species',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function petApplications()
    {
        return $this->hasMany(PetFleaTickApplication::class, 'flea_tick_protection_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeBySpecies($query, string $species)
    {
        return $query->where('species', 'like', "%{$species}%");
    }
}
