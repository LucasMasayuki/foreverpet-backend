<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetShare extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'user_id',
        'shared_user_id',
        'code',
        'access_level',
        'status',
    ];

    protected $casts = [
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

    public function sharedUser()
    {
        return $this->belongsTo(User::class, 'shared_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByAccessLevel($query, string $level)
    {
        return $query->where('access_level', $level);
    }
}
