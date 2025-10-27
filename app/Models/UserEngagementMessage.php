<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEngagementMessage extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
