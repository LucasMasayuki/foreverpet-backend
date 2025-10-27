<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiderProduct extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'source',
        'external_id',
        'name',
        'description',
        'price',
        'sale_price',
        'currency',
        'image_url',
        'product_url',
        'category',
        'brand',
        'in_stock',
        'last_scraped_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'in_stock' => 'boolean',
        'last_scraped_at' => 'datetime',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', 'like', "%{$category}%");
    }

    public function hasDiscount(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }
}
