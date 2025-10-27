<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetFelv extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pet_id',
        'vet_id',
        'date',
        'felv_result',
        'fiv_result',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function scopePositive($query, string $type = 'felv')
    {
        $field = $type === 'felv' ? 'felv_result' : 'fiv_result';
        return $query->where($field, 'positive');
    }

    public function scopeNegative($query, string $type = 'felv')
    {
        $field = $type === 'felv' ? 'felv_result' : 'fiv_result';
        return $query->where($field, 'negative');
    }
}
