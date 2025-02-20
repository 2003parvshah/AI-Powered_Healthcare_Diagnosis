<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'current_hospital_clinic',
        'previous_workplaces',
        'internship_residency_details',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
