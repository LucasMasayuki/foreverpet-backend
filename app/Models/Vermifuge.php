<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vermifuge extends Model
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

    public function petVermifuges()
    {
        return $this->hasMany(PetVermifuge::class, 'vermifuge_id');
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
