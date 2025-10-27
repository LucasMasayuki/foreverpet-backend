<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'species_id',
        'subspecies_id',
        'race_id',
        'vet_id',
        'name',
        'gender',
        'birthdate',
        'size',
        'picture',
        'picture_small',
        'fur',
        'castrated',
        'pedigree',
        'weight',
        'microchip_number',
        'microchip_serial_number',
        'deceased',
        'deceased_date',
        'tracking_device_number',
        'tracking_device_password',
        'visibility',
        'status',
        'instagram',
        'facebook',
        'qr_code',
        'qr_code_key',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'castrated' => 'boolean',
        'pedigree' => 'boolean',
        'weight' => 'double',
        'deceased' => 'boolean',
        'deceased_date' => 'date',
        'created_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function species()
    {
        return $this->belongsTo(PetSpecies::class, 'species_id');
    }

    public function subspecies()
    {
        return $this->belongsTo(PetSubspecies::class, 'subspecies_id');
    }

    public function race()
    {
        return $this->belongsTo(PetRace::class, 'race_id');
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    public function vaccines()
    {
        return $this->hasMany(PetVaccine::class, 'pet_id');
    }

    public function vaccineDoses()
    {
        return $this->hasMany(PetVaccineDose::class, 'pet_id');
    }

    public function medications()
    {
        return $this->hasMany(PetMedication::class, 'pet_id');
    }

    public function medicationDoses()
    {
        return $this->hasMany(PetMedicationDose::class, 'pet_id');
    }

    public function fleaTickApplications()
    {
        return $this->hasMany(PetFleaTickApplication::class, 'pet_id');
    }

    public function vermifuges()
    {
        return $this->hasMany(PetVermifuge::class, 'pet_id');
    }

    public function weights()
    {
        return $this->hasMany(PetWeight::class, 'pet_id');
    }

    public function exams()
    {
        return $this->hasMany(PetExam::class, 'pet_id');
    }

    public function heats()
    {
        return $this->hasMany(PetHeat::class, 'pet_id');
    }

    public function lostRecords()
    {
        return $this->hasMany(PetLost::class, 'pet_id');
    }

    public function scheduledEvents()
    {
        return $this->hasMany(PetScheduledEvent::class, 'pet_id');
    }

    public function shares()
    {
        return $this->hasMany(PetShare::class, 'pet_id');
    }

    public function bathCoughs()
    {
        return $this->hasMany(PetBathCough::class, 'pet_id');
    }

    public function felvs()
    {
        return $this->hasMany(PetFelv::class, 'pet_id');
    }

    public function widths()
    {
        return $this->hasMany(PetWidth::class, 'pet_id');
    }

    public function ecdysis()
    {
        return $this->hasMany(PetEcdysis::class, 'pet_id');
    }

    public function hygienes()
    {
        return $this->hasMany(PetHygiene::class, 'pet_id');
    }

    public function userPictures()
    {
        return $this->hasMany(UserPicturePet::class, 'pet_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeBySpecies($query, string $speciesId)
    {
        return $query->where('species_id', $speciesId);
    }

    public function scopeByUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDeceased($query)
    {
        return $query->where('deceased', true);
    }

    public function scopeAlive($query)
    {
        return $query->where('deceased', false);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function isDeceased(): bool
    {
        return $this->deceased === true;
    }

    public function isCastrated(): bool
    {
        return $this->castrated === true;
    }

    public function hasPedigree(): bool
    {
        return $this->pedigree === true;
    }

    public function hasMicrochip(): bool
    {
        return !empty($this->microchip_number);
    }
}
