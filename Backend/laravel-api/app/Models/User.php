<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
      'id' ,  'name', 'email', 'password', 'role', 'current_team_id', 'profile_photo_path', 'doctor_id', 'patient_id'
    ];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    // public function profile()
    // {
    //     return $this->hasOne(Doctor::class);
    // }
    /**
     * Get the identifier for the user.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get custom claims for the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
