<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'specialization_id',
        'degree_id',
        'license_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function degree()
    {
        return $this->belongsTo(MedicalDegree::class);
    }

    public function personalInfo()
    {
        return $this->hasOne(DoctorPersonalInfo::class);
    }

    public function contactInfo()
    {
        return $this->hasOne(DoctorContactInfo::class);
    }

    public function professionalInfo()
    {
        return $this->hasOne(DoctorProfessionalInfo::class);
    }

    public function workExperience()
    {
        return $this->hasOne(DoctorWorkExperience::class);
    }

    public function availability()
    {
        return $this->hasOne(DoctorAvailability::class);
    }

    public function fees()
    {
        return $this->hasOne(DoctorFees::class);
    }

   
}
