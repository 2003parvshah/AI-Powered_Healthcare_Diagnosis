<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentFeedback extends Model
{
    protected $fillable = ['appointment_id', 'rating', 'feedback'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
