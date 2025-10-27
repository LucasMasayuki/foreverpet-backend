<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'type',
        'status',
        'target_audience',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function sendings()
    {
        return $this->hasMany(CampaignSending::class, 'campaign_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', now());
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent')->whereNotNull('sent_at');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
