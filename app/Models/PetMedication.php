<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetMedication extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'medication_id',
        'vet_id',
        'name',
        'notes',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class, 'medication_id');
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function doses()
    {
        return $this->hasMany(PetMedicationDose::class, 'pet_medication_id');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }

    public function isActive(): bool
    {
        return $this->end_date === null || $this->end_date >= now();
    }
}
