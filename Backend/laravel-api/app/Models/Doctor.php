<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $primaryKey = 'doctor_id'; // Set primary key
    public $incrementing = false; // Prevent Laravel from assuming it's an auto-incrementing ID

    protected $fillable = [
        'doctor_id',
        'specialization',
        'license_number',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
