<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorHoliday extends Model
{
    //




    use HasFactory;

    protected $table = 'doctor_holiday'; // Explicit table name

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'timezone',
        'byDoctor',
    ];

    // Relationship with Doctor Model
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
