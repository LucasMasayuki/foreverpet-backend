<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'subject',
        'message',
        'email',
        'phone',
        'status',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
    ];

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

    public function scopeBySubject($query, string $subject)
    {
        return $query->where('subject', 'like', "%{$subject}%");
    }
}
