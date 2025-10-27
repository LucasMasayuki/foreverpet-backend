<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetMedicationDose extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_medication_id',
        'pet_id',
        'date',
        'time',
        'dosage',
        'notes',
        'taken',
    ];

    protected $casts = [
        'date' => 'date',
        'taken' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function petMedication()
    {
        return $this->belongsTo(PetMedication::class, 'pet_medication_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function scopeTaken($query)
    {
        return $query->where('taken', true);
    }

    public function scopePending($query)
    {
        return $query->where('taken', false);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }
}
