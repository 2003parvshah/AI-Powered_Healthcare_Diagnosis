<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'license_number',
        'bio',
        'medical_degrees',
        'years_of_experience',
        'board_certifications',
        'university_attended',
        'medical_council_registration',
        'professional_memberships',
        'research_publications',
        'current_hospital',
        'previous_workplaces',
        'consultation_hours',
        'online_consultation',
        'walk_in_availability',
        'appointment_required',
        'max_patients_per_day',
        'appointment_duration',
        'surgical_expertise',
        'treatment_approach',
        'consultation_fees',
        'payment_methods',
        'average_rating',
        'awards_recognitions',
        'website_url',
        'linkedin_profile',
        'twitter_handle',
        'youtube_channel'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
