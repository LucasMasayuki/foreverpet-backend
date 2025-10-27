<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetVaccine extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'vaccine_id',
        'vet_id',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class, 'vaccine_id');
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function doses()
    {
        return $this->hasMany(PetVaccineDose::class, 'pet_vaccine_id');
    }
}
