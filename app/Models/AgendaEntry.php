<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaEntry extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'pet_id',
        'vet_id',
        'type',
        'title',
        'description',
        'date',
        'time',
        'location',
        'status',
        'reminder_sent',
    ];

    protected $casts = [
        'date' => 'date',
        'reminder_sent' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', '!=', 'completed')
            ->where('date', '>=', now())
            ->orderBy('date');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
