<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'vet_id',
        'ong_id',
        'name',
        'email',
        'password',
        'picture',
        'birthdate',
        'gender',
        'phone_number_country_code',
        'phone_number',
        'address',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_country',
        'address_postal_code',
        'internal',
        'beta_tester',
        'status',
        'register_complete',
        'facebook_id',
        'google_id',
        'apple_id',
        'twitter_id',
        'terms_and_conditions_accepted',
        'privacy_accepted',
        'phone_number_confirmed',
        'is_pro_user',
        'inactive',
        'feature_flags',
    ];

    protected $hidden = [
        'password',
        'password_reset_token',
        'qr_code_login_key',
        'phone_number_confirmation_code',
        'email_challenge_code',
        'sms_challenge_code',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'internal' => 'boolean',
        'beta_tester' => 'boolean',
        'register_complete' => 'boolean',
        'terms_and_conditions_accepted' => 'boolean',
        'terms_and_conditions_accepted_date' => 'datetime',
        'privacy_accepted' => 'boolean',
        'privacy_accepted_date' => 'datetime',
        'phone_number_confirmed' => 'boolean',
        'phone_number_confirmation_code_sent_at' => 'datetime',
        'removed' => 'boolean',
        'removed_at' => 'datetime',
        'logoff_required' => 'boolean',
        'is_pro_user' => 'boolean',
        'email_challenge_code_sent_at' => 'datetime',
        'sms_challenge_code_sent_at' => 'datetime',
        'inactive' => 'boolean',
        'block_phone' => 'boolean',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_visit_at' => 'datetime',
        'presumed_address_filled_by_user' => 'boolean',
    ];

    // Relationships
    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function ong()
    {
        return $this->belongsTo(Ong::class, 'ong_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'user_id');
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }

    public function deviceHistories()
    {
        return $this->hasMany(UserDeviceHistory::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    public function creditCards()
    {
        return $this->hasMany(UserCreditCard::class, 'user_id');
    }

    public function pictures()
    {
        return $this->hasMany(UserPicture::class, 'user_id');
    }

    public function picturePets()
    {
        return $this->hasMany(UserPicturePet::class, 'user_id');
    }

    public function engagementMessages()
    {
        return $this->hasMany(UserEngagementMessage::class, 'user_id');
    }

    public function agendaEntries()
    {
        return $this->hasMany(AgendaEntry::class, 'user_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function campaignSendings()
    {
        return $this->hasMany(CampaignSendingUser::class, 'user_id');
    }

    public function storeOrders()
    {
        return $this->hasMany(StoreOrder::class, 'user_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeRemoved($query)
    {
        return $query->where('removed', true);
    }

    public function scopeProUsers($query)
    {
        return $query->where('is_pro_user', true);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function isRemoved(): bool
    {
        return $this->removed === true;
    }

    public function isProUser(): bool
    {
        return $this->is_pro_user === true;
    }

    public function hasCompletedRegistration(): bool
    {
        return $this->register_complete === true;
    }

    public function hasAcceptedTerms(): bool
    {
        return $this->terms_and_conditions_accepted === true;
    }
}
