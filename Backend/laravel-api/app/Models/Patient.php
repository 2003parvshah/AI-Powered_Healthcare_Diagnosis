<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'id',
        'date_of_birth',
        'gender',
        'medical_history',
        'phone_number',
        'address',
        'profile_photo',
        'past_medical_conditions',
        'allergies',
        'blood_pressure',
        'weight',
        'blood_group',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function healthIssues()
    {
        return $this->hasMany(HealthIssue::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
