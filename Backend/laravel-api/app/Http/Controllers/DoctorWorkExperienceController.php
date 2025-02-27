<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorWorkExperience;
use Tymon\JWTAuth\Facades\JWTAuth;

class DoctorWorkExperienceController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorWorkExperience(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'current_hospital_clinic' => 'required|string',
            'previous_workplaces' => 'nullable|string',
            'internship_residency_details' => 'nullable|string',
        ]);

        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Create or update work experience record
        $workExperience = DoctorWorkExperience::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            [
                'current_hospital_clinic' => $request->current_hospital_clinic,
                'previous_workplaces' => $request->previous_workplaces,
                'internship_residency_details' => $request->internship_residency_details,
                'experience' => $request->experience,
            ]
        );

        return response()->json([
            'message' => 'Doctor work experience updated successfully!',
            'data' => $workExperience,
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorWorkExperience()
    {
        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve work experience for the doctor
        $workExperience = DoctorWorkExperience::where('doctor_id', $user->id)->first();

        if (!$workExperience) {
            return response()->json(['message' => 'No work experience found for this doctor'], 404);
        }

        return response()->json(['data' => $workExperience], 200);
    }
}
