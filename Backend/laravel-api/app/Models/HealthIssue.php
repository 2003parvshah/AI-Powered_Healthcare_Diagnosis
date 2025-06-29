<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthIssue extends Model
{
    use HasFactory;

    protected $table = 'health_issues';

    protected $fillable = [
        'patient_id',
        'diagnosis',
        'solution',
        'symptoms',
        'report_pdf',
        'report_image',

    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class,  'patient_id', 'id');
    }
}
