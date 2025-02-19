<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'patient_id';
    public $incrementing = false;

    protected $fillable = [
        'patient_id',
        'date_of_birth',
        'gender',
        'medical_history',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
