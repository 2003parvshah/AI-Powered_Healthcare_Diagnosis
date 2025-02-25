<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Doctor;
use App\DTOs\DoctorDTO;

class DoctorController extends Controller
{
    public function getAllInfoDoctors()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $doctors = Doctor::where('doctors.id', $user->id)
            ->leftJoin('doctor_work_experience as w', 'doctors.id', '=', 'w.doctor_id')
            ->leftJoin('doctor_professional_info as e', 'doctors.id', '=', 'e.doctor_id')
            ->leftJoin('doctor_fees as f', 'doctors.id', '=', 'f.doctor_id')
            ->leftJoin('doctor_contact_info as c', 'doctors.id', '=', 'c.doctor_id')
            ->leftJoin('doctor_personal_info as p', 'doctors.id', '=', 'p.doctor_id')
            ->leftJoin('doctor_availability as h', 'doctors.id', '=', 'h.doctor_id')
            ->select([
                'doctors.id',
                'doctors.specialization_id',
                'doctors.degree_id',
                'doctors.license_number',

                'w.current_hospital_clinic',
                'w.experience',
                'w.previous_workplaces',
                'w.internship_residency_details',

                'e.board_certifications',
                'e.university_college_attended',
                'e.medical_council_registration_number',
                'e.professional_memberships',
                'e.research_publications',

                'f.consultation_fees',
                'f.payment_methods_accepted',

                'c.primary_phone_number',
                'c.home_address',
                'c.clinic_hospital_address',

                'p.date_of_birth',
                'p.gender',
                'p.profile_photo',
                'p.nationality',
                'p.languages_spoken',

                'h.consultation_hours',
                'h.online_consultation_availability',
                'h.walk_in_availability',
                'h.appointment_booking_required',
                'h.time_of_one_appointment'
            ])
            ->first();

        // Convert each row into DTO format
        // $formattedDoctors = $doctors->map(fn($doctor) => new DoctorDTO($doctor));

        return response()->json($doctors);
    }
}
