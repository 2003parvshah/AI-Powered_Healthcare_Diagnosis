<?php

namespace App\DTOs;

class DoctorScheduleDTO
{
    public $doctor_id;
    public $start_time;
    public $available;
    public $address;
    public $timezone;

    public function __construct($doctor_id, $start_time, $available, $address, $timezone)
    {
        $this->doctor_id = $doctor_id;
        $this->start_time = $start_time;
        $this->available = $available;
        $this->address = $address;
        $this->timezone = $timezone;
    }

    public function toArray()
    {
        return [
            'doctor_id' => $this->doctor_id,
            'start_time' => $this->start_time,
            'available' => $this->available,
            'address' => $this->address,
            'timezone' => $this->timezone
        ];
    }
}
