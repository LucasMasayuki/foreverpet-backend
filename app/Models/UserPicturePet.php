<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPicturePet extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_picture_id',
        'user_id',
        'pet_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function userPicture()
    {
        return $this->belongsTo(UserPicture::class, 'user_picture_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
