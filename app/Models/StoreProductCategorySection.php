<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProductCategorySection extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'enabled',
        'order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(StoreProductCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(StoreProduct::class, 'section_id');
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
