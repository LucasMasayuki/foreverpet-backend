<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'store_id',
        'category_id',
        'section_id',
        'name',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'currency',
        'stock_quantity',
        'image_url',
        'product_url',
        'enabled',
        'featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'enabled' => 'boolean',
        'featured' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function category()
    {
        return $this->belongsTo(StoreProductCategory::class, 'category_id');
    }

    public function section()
    {
        return $this->belongsTo(StoreProductCategorySection::class, 'section_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(StoreOrderProduct::class, 'product_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
            ->where('sale_price', '<', 'price');
    }

    public function hasDiscount(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}
