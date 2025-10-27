<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'status',
        'areas',
        'theme',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }
}
