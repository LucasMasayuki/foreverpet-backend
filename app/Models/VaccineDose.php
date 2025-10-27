<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccineDose extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'vaccine_id',
        'name',
        'description',
        'enabled',
        'order',
        'notification_message',
        'days_after_last_dose',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
        'days_after_last_dose' => 'integer',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class, 'vaccine_id');
    }

    public function petVaccineDoses()
    {
        return $this->hasMany(PetVaccineDose::class, 'vaccine_dose_id');
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
