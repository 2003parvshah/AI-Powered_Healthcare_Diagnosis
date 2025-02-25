<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorFees extends Model
{
    use HasFactory;
    protected $table = 'doctor_fees';

    protected $fillable = [
        'doctor_id',
        'consultation_fees',
        'payment_methods_accepted',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
