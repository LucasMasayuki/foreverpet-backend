<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetVaccineDoseNotification extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_vaccine_dose_id',
        'pet_id',
        'user_id',
        'notification_date',
        'sent',
    ];

    protected $casts = [
        'notification_date' => 'date',
        'sent' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function petVaccineDose()
    {
        return $this->belongsTo(PetVaccineDose::class, 'pet_vaccine_dose_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePending($query)
    {
        return $query->where('sent', false);
    }

    public function scopeSent($query)
    {
        return $query->where('sent', true);
    }
}
