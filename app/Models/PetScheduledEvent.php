<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetScheduledEvent extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'user_id',
        'event_type',
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'completed',
        'reminder_sent',
    ];

    protected $casts = [
        'event_date' => 'date',
        'completed' => 'boolean',
        'reminder_sent' => 'boolean',
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

    public function scopeUpcoming($query)
    {
        return $query->where('completed', false)
            ->where('event_date', '>=', now())
            ->orderBy('event_date');
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('completed', false)
            ->where('event_date', '<', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('event_date', today());
    }
}
