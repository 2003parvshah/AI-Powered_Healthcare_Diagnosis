<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // protected $primaryKey = 'patient_id';
    // public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id' ,
        'date_of_birth',
        'gender',
        'medical_history',
        'phone_number', 
        'email',
         'address',
        'emergency_contact_name',
         'emergency_contact_phone',
          'past_medical_conditions',
        'allergies',
         'blood_pressure',
          'weight',
           'blood_group'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
