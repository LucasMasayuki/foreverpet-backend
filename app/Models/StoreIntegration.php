<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIntegration extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'store_id',
        'type',
        'api_key',
        'api_secret',
        'webhook_url',
        'settings',
        'enabled',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    protected $casts = [
        'settings' => 'json',
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
