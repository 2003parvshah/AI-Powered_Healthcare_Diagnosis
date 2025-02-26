<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Week;


class Doctor_timeTable extends Model
{
    use HasFactory;

    protected $table = 'doctor_timeTable'; // Table name

    protected $fillable = [
        'doctor_id',
        'day',
        'start_time',
        'end_time',
        'timezone',
        'address'
    ];

    /**
     * Get the doctor associated with this timing.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the day of the week for this timing.
     */
    public function week()
    {
        return $this->belongsTo(Week::class, 'day');
    }
}
