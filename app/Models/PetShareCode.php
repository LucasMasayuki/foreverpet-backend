<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetShareCode extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'code',
        'access_level',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function scopeActive($query)
    {
        return $query->where('used', false)
            ->where('expires_at', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    public function isUsed(): bool
    {
        return $this->used === true;
    }
}
