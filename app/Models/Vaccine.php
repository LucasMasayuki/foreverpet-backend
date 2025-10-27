<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'enabled',
        'species',
        'order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function doses()
    {
        return $this->hasMany(VaccineDose::class, 'vaccine_id');
    }

    public function petVaccines()
    {
        return $this->hasMany(PetVaccine::class, 'vaccine_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeBySpecies($query, string $species)
    {
        return $query->where('species', 'like', "%{$species}%");
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
