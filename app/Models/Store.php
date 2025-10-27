<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'logo',
        'enabled',
        'featured_order',
        'shipping_info',
        'return_policy',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'featured_order' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(StoreProduct::class, 'store_id');
    }

    public function integrations()
    {
        return $this->hasMany(StoreIntegration::class, 'store_id');
    }

    public function orders()
    {
        return $this->hasMany(StoreOrder::class, 'store_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured_order', '>', 0)->orderBy('featured_order');
    }
}
