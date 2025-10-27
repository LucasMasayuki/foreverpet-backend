<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetHeat extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'start_date',
        'end_date',
        'notes',
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

    public function scopeActive($query)
    {
        return $query->whereNull('end_date')
            ->orWhere('end_date', '>=', now());
    }

    public function isActive(): bool
    {
        return $this->end_date === null || $this->end_date >= now();
    }
}
