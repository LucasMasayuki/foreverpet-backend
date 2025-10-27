<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationCategory extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'enabled',
        'order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function medications()
    {
        return $this->hasMany(Medication::class, 'category_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
