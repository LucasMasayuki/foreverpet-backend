<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class ProfessionalUser extends Authenticatable
{
    use HasApiTokens, SoftDeletes, HasRoles;

    protected $guard_name = 'professional';
    protected $table = 'professional_users';

    protected $fillable = [
        'email',
        'password',
        'name',
        'professional_type',
        'professional_id',
        'professional_table',
        'status',
        'email_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'last_login_at',
        'last_login_ip',
        'last_user_agent',
        'preferences',
        'theme',
        'language',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'preferences' => 'json',
    ];

    // Polymorphic relationship to professional profile
    public function professional()
    {
        if ($this->professional_table === 'vets') {
            return $this->belongsTo(Vet::class, 'professional_id');
        }

        if ($this->professional_table === 'ongs') {
            return $this->belongsTo(Ong::class, 'professional_id');
        }

        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('professional_type', $type);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->professional_type === 'admin';
    }

    public function isVet(): bool
    {
        return $this->professional_type === 'vet';
    }

    public function isOng(): bool
    {
        return $this->professional_type === 'ong';
    }

    public function isStaff(): bool
    {
        return $this->professional_type === 'staff';
    }

    public function isManager(): bool
    {
        return $this->professional_type === 'manager';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled === true;
    }
}
