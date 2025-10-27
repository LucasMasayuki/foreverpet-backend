<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSendingUser extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'campaign_sending_id',
        'user_id',
        'sent',
        'sent_at',
        'opened',
        'opened_at',
        'clicked',
        'clicked_at',
    ];

    protected $casts = [
        'sent' => 'boolean',
        'sent_at' => 'datetime',
        'opened' => 'boolean',
        'opened_at' => 'datetime',
        'clicked' => 'boolean',
        'clicked_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function campaignSending()
    {
        return $this->belongsTo(CampaignSending::class, 'campaign_sending_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSent($query)
    {
        return $query->where('sent', true);
    }

    public function scopeOpened($query)
    {
        return $query->where('opened', true);
    }

    public function scopeClicked($query)
    {
        return $query->where('clicked', true);
    }
}
