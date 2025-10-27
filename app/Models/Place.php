<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'vet_id',
        'ong_id',
        'type',
        'name',
        'description',
        'address',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_country',
        'address_postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'picture',
        'featured_order',
        'enabled',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
        'featured_order' => 'integer',
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function ong()
    {
        return $this->belongsTo(Ong::class, 'ong_id');
    }

    public function qualifications()
    {
        return $this->hasMany(PlaceQualification::class, 'place_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured_order', '>', 0)->orderBy('featured_order');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('address_city', $city);
    }
}
