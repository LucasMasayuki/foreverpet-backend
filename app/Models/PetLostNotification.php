<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetLostNotification extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_lost_id',
        'user_id',
        'latitude',
        'longitude',
        'distance_km',
        'sent',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
        'distance_km' => 'double',
        'sent' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function petLost()
    {
        return $this->belongsTo(PetLost::class, 'pet_lost_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSent($query)
    {
        return $query->where('sent', true);
    }

    public function scopePending($query)
    {
        return $query->where('sent', false);
    }
}
