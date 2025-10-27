<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOrderProduct extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(StoreOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'product_id');
    }
}
