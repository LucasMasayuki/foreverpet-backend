<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreditCard extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'token',
        'brand',
        'last_digits',
        'holder_name',
        'expiration_month',
        'expiration_year',
        'is_default',
    ];

    protected $hidden = [
        'token',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'expiration_month' => 'integer',
        'expiration_year' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function isExpired(): bool
    {
        $now = now();
        return $this->expiration_year < $now->year ||
               ($this->expiration_year == $now->year && $this->expiration_month < $now->month);
    }
}
