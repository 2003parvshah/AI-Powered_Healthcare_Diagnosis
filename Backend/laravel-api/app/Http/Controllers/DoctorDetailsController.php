<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DTOs\DoctorDetailsDTO;

class DoctorDetailsController extends Controller
{
    public function getAllDoctorDetails()
    {
        $doctorDetails = DB::table('users')
            ->join('doctors', 'users.id', '=', 'doctors.id')
            ->leftjoin('doctor_work_experience', 'users.id', '=', 'doctor_work_experience.doctor_id')
            ->leftjoin('doctor_fees', 'users.id', '=', 'doctor_fees.doctor_id')
            ->leftjoin('doctor_personal_info', 'users.id', '=', 'doctor_personal_info.doctor_id')
            // ->where('users.id', $doctorId)
            ->select(
                'users.name',
                'doctors.id',
                'doctors.specialization_id',
                'doctor_work_experience.experience',
                'doctor_fees.consultation_fees',
                'doctor_personal_info.profile_photo'
            )
            ->get();

        if (!$doctorDetails) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Create a DTO instance
        $doctorList = $doctorDetails->map(function ($doctor) {
            return new DoctorDetailsDTO(
                $doctor->name,
                $doctor->specialization_id,
                $doctor->experience,
                $doctor->consultation_fees,
                $doctor->profile_photo,
                $doctor->id
            );
        });

        return response()->json($doctorList, 200);
    }
}
