<?php

namespace App\DTOs;

class DoctorDetailsDTO
{
    public string $name;
    public string $specialization;
    public ?int $experience;
    public ?float $consultation_fees;
    public ?string $profile_photo;

    public function __construct($name, $specialization, $experience, $consultation_fees, $profile_photo)
    {
        $this->name = $name;
        $this->specialization = $specialization;
        $this->experience = $experience;
        $this->consultation_fees = $consultation_fees;
        $this->profile_photo = $profile_photo;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'specialization' => $this->specialization,
            'experience' => $this->experience,
            'consultation_fees' => $this->consultation_fees,
            'profile_photo' => $this->profile_photo,
        ];
    }
}
