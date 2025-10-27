<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPicture extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'url',
        'url_small',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pets()
    {
        return $this->hasMany(UserPicturePet::class, 'user_picture_id');
    }
}
