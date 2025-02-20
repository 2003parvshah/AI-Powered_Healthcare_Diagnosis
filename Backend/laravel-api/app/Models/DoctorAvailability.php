<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'consultation_hours',
        'online_consultation_availability',
        'walk_in_availability',
        'appointment_booking_required',
        'time_of_one_appointment',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
