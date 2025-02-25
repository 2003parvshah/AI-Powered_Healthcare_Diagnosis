<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPersonalInfo extends Model
{
    use HasFactory;
    protected $table = 'doctor_personal_info';


    protected $fillable = [
        'doctor_id',
        'full_name',
        'date_of_birth',
        'gender',
        'profile_photo',
        'nationality',
        'languages_spoken',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
