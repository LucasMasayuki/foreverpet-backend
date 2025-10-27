<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetSubspecies extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'pet_subspecies';

    protected $fillable = [
        'id',
        'species_id',
        'name',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function species()
    {
        return $this->belongsTo(PetSpecies::class, 'species_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'subspecies_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
