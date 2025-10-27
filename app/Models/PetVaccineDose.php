<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetVaccineDose extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_vaccine_id',
        'pet_id',
        'vaccine_dose_id',
        'vet_id',
        'date',
        'next_dose_date',
        'brand',
        'batch',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'next_dose_date' => 'date',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function petVaccine()
    {
        return $this->belongsTo(PetVaccine::class, 'pet_vaccine_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function vaccineDose()
    {
        return $this->belongsTo(VaccineDose::class, 'vaccine_dose_id');
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function notifications()
    {
        return $this->hasMany(PetVaccineDoseNotification::class, 'pet_vaccine_dose_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('next_dose_date')
            ->where('next_dose_date', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('next_dose_date')
            ->where('next_dose_date', '<', now());
    }
}
