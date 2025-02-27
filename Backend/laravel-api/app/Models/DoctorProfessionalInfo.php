<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfessionalInfo extends Model
{
    use HasFactory;
    protected $table = 'doctor_professional_info';

    protected $fillable = [
        'doctor_id',
        'board_certifications',
        'university_college_attended',
        'medical_council_registration_number',
        'professional_memberships',
        'research_publications',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
