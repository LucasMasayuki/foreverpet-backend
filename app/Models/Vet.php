<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vet extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'type',
        'name',
        'email',
        'website',
        'picture',
        'crmv',
        'phone_number',
        'celphone_number',
        'address',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_state_other',
        'address_country',
        'address_postal_code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class, 'vet_id');
    }

    public function professionalUsers()
    {
        return $this->hasMany(ProfessionalUser::class, 'professional_id')
            ->where('professional_table', 'vets');
    }

    public function places()
    {
        return $this->hasMany(Place::class, 'vet_id');
    }

    public function agendaEntries()
    {
        return $this->hasMany(AgendaEntry::class, 'vet_id');
    }

    // Scopes
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('address_city', $city);
    }

    // Helpers
    public function isVet(): bool
    {
        return $this->type === 'Vet';
    }

    public function isClinic(): bool
    {
        return $this->type === 'Clinic';
    }

    public function isBreeder(): bool
    {
        return $this->type === 'Breeder';
    }
}
