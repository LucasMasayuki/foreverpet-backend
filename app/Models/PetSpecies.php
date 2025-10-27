<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetSpecies extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'pet_species';

    protected $fillable = [
        'id',
        'name',
        'name_plural',
        'enabled',
        'featured_order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'featured_order' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class, 'species_id');
    }

    public function subspecies()
    {
        return $this->hasMany(PetSubspecies::class, 'species_id');
    }

    public function races()
    {
        return $this->hasMany(PetRace::class, 'species_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured_order', '>', 0)->orderBy('featured_order');
    }
}
