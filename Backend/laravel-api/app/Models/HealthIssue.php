<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'symptoms',
        'report_pdf',
        'report_image',
        'doctor_type',
        'diagnosis',
        'solution',
        'other_info'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
