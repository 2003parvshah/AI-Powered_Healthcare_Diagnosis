<?php

namespace App\DTOs;

class AppointmentsDTO
{
    public $id;
    public $doctor_id;
    public $patient_id;
    public $health_issues_id;
    public $appointment_date;
    public $created_at;
    public $updated_at;

    public function __construct($data)
    {
        $this->id = $data->id;
        $this->doctor_id = $data->doctor_id;
        $this->patient_id = $data->patient_id;
        $this->health_issues_id = $data->health_issues_id;
        $this->appointment_date = $data->appointment_date;
        $this->created_at = $data->created_at;
        $this->updated_at = $data->updated_at;
    }
}
