<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ong extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'category',
        'name',
        'document_number',
        'description',
        'address',
        'city',
        'state',
        'state_other',
        'country',
        'postal_code',
        'email',
        'phone_numbers',
        'site',
        'picture',
        'logo',
        'featured_order',
        'species',
        'latitude',
        'longitude',
        'enabled',
        'password',
        'enable_user',
        'theme',
        'app_user_emails',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'featured_order' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'enabled' => 'boolean',
        'enable_user' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class, 'ong_id');
    }

    public function professionalUsers()
    {
        return $this->hasMany(ProfessionalUser::class, 'professional_id')
            ->where('professional_table', 'ongs');
    }

    public function places()
    {
        return $this->hasMany(Place::class, 'ong_id');
    }

    public function adoptionContacts()
    {
        return $this->hasMany(OngAdoptionContact::class, 'ong_id');
    }

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured_order', '>', 0)
            ->orderBy('featured_order');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    // Helpers
    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }

    public function isFeatured(): bool
    {
        return $this->featured_order > 0;
    }

    public function hasUserAccess(): bool
    {
        return $this->enable_user === true;
    }
}
