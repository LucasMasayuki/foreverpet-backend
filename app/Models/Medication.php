<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'enabled',
        'species',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(MedicationCategory::class, 'category_id');
    }

    public function petMedications()
    {
        return $this->hasMany(PetMedication::class, 'medication_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeByCategory($query, string $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeBySpecies($query, string $species)
    {
        return $query->where('species', 'like', "%{$species}%");
    }
}
