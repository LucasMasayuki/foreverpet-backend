<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'device_uuid',
        'device_token',
        'platform',
        'app_version',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function histories()
    {
        return $this->hasMany(UserDeviceHistory::class, 'device_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeByPlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }
}
