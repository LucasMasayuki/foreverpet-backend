<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeviceHistory extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'device_id',
        'device_uuid',
        'device_token',
        'platform',
        'app_version',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function device()
    {
        return $this->belongsTo(UserDevice::class, 'device_id');
    }
}
