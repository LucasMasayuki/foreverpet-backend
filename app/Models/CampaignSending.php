<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSending extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'campaign_id',
        'title',
        'message',
        'image_url',
        'link_url',
        'target_audience',
        'status',
        'scheduled_at',
        'sent_at',
        'total_sent',
        'total_opened',
        'total_clicked',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'total_sent' => 'integer',
        'total_opened' => 'integer',
        'total_clicked' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function userSendings()
    {
        return $this->hasMany(CampaignSendingUser::class, 'campaign_sending_id');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent')->whereNotNull('sent_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->whereNotNull('scheduled_at');
    }
}
