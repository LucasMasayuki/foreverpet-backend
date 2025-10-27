<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetLost extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'user_id',
        'date_lost',
        'date_found',
        'location',
        'latitude',
        'longitude',
        'description',
        'reward',
        'contact_info',
        'status',
    ];

    protected $casts = [
        'date_lost' => 'datetime',
        'date_found' => 'datetime',
        'latitude' => 'double',
        'longitude' => 'double',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(PetLostNotification::class, 'pet_lost_id');
    }

    public function scopeLost($query)
    {
        return $query->whereNull('date_found')->where('status', 'lost');
    }

    public function scopeFound($query)
    {
        return $query->whereNotNull('date_found')->where('status', 'found');
    }

    public function isLost(): bool
    {
        return $this->status === 'lost' && $this->date_found === null;
    }

    public function isFound(): bool
    {
        return $this->status === 'found' && $this->date_found !== null;
    }
}
