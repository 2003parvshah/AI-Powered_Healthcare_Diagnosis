<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorContactInfo extends Model
{
    use HasFactory;
    protected $table = 'doctor_contact_info';

    protected $fillable = [
        'doctor_id',
        'primary_phone_number',
        'home_address',
        'clinic_hospital_address',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
