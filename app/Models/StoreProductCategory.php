<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProductCategory extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'icon',
        'enabled',
        'order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function sections()
    {
        return $this->hasMany(StoreProductCategorySection::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(StoreProduct::class, 'category_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
