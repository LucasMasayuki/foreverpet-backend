<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceQualification extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'place_id',
        'user_id',
        'rating',
        'comment',
        'approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeByRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }
}
