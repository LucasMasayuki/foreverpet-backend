<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OngAdoptionContact extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'ong_id',
        'user_id',
        'pet_name',
        'pet_species',
        'contact_name',
        'contact_email',
        'contact_phone',
        'message',
        'status',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function ong()
    {
        return $this->belongsTo(Ong::class, 'ong_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
