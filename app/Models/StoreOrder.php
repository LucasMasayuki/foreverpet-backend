<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'shipping_cost',
        'tax',
        'total',
        'currency',
        'payment_method',
        'payment_status',
        'shipping_address',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_address' => 'json',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(StoreOrderProduct::class, 'order_id');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
