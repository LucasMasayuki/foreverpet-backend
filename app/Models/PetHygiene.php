<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetHygiene extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'date',
        'type',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
