<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetVermifuge extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'vermifuge_id',
        'date',
        'next_application_date',
        'brand',
        'dosage',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'next_application_date' => 'date',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function vermifuge()
    {
        return $this->belongsTo(Vermifuge::class, 'vermifuge_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('next_application_date')
            ->where('next_application_date', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('next_application_date')
            ->where('next_application_date', '<', now());
    }
}
