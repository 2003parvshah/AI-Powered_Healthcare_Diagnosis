<?php

namespace App\DTOs;

class DoctorDTO
{
    public $id;
    public $doctor_id;
    public $name;
    public $date_of_birth;
    public $gender;
    public $profile_photo;
    public $nationality;
    public $languages_spoken;

    // Work Experience
    public $current_hospital_clinic;
    public $experience;
    public $previous_workplaces;
    public $internship_residency_details;

    // Professional Info
    public $board_certifications;
    public $university_college_attended;
    public $medical_council_registration_number;
    public $professional_memberships;
    public $research_publications;

    // Fees
    public $consultation_fees;
    public $payment_methods_accepted;

    // Contact Info
    public $primary_phone_number;
    public $home_address;
    public $clinic_hospital_address;

    // Availability
    public $consultation_hours;
    public $online_consultation_availability;
    public $walk_in_availability;
    public $appointment_booking_required;
    public $time_of_one_appointment;

    public function __construct($data)
    {
        $this->id = $data->id;
        $this->doctor_id = $data->doctor_id;
        $this->name = $data->name;
        $this->date_of_birth = $data->date_of_birth;
        $this->gender = $data->gender;
        $this->profile_photo = $data->profile_photo;
        $this->nationality = $data->nationality;
        $this->languages_spoken = $data->languages_spoken;

        // Work Experience
        $this->current_hospital_clinic = $data->current_hospital_clinic;
        $this->experience = $data->experience;
        $this->previous_workplaces = $data->previous_workplaces;
        $this->internship_residency_details = $data->internship_residency_details;

        // Professional Info
        $this->board_certifications = $data->board_certifications;
        $this->university_college_attended = $data->university_college_attended;
        $this->medical_council_registration_number = $data->medical_council_registration_number;
        $this->professional_memberships = $data->professional_memberships;
        $this->research_publications = $data->research_publications;

        // Fees
        $this->consultation_fees = $data->consultation_fees;
        $this->payment_methods_accepted = $data->payment_methods_accepted;

        // Contact Info
        $this->primary_phone_number = $data->primary_phone_number;
        $this->home_address = $data->home_address;
        $this->clinic_hospital_address = $data->clinic_hospital_address;

        // Availability
        $this->consultation_hours = $data->consultation_hours;
        $this->online_consultation_availability = $data->online_consultation_availability;
        $this->walk_in_availability = $data->walk_in_availability;
        $this->appointment_booking_required = $data->appointment_booking_required;
        $this->time_of_one_appointment = $data->time_of_one_appointment;
    }
}
