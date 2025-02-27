<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorProfessionalInfo;
use Tymon\JWTAuth\Facades\JWTAuth;

class DoctorProfessionalInfoController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorProfessionalInfo(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'board_certifications' => 'required|string',
            'university_college_attended' => 'required|string',
            'medical_council_registration_number' => 'required|string|unique:doctor_professional_info,medical_council_registration_number',
            'professional_memberships' => 'nullable|string',
            'research_publications' => 'nullable|string',
        ]);

        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Create or update professional info record
        $professionalInfo = DoctorProfessionalInfo::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            [
                'board_certifications' => $request->board_certifications,
                'university_college_attended' => $request->university_college_attended,
                'medical_council_registration_number' => $request->medical_council_registration_number,
                'professional_memberships' => $request->professional_memberships,
                'research_publications' => $request->research_publications,
            ]
        );

        return response()->json([
            'message' => 'Doctor professional info updated successfully!',
            'data' => $professionalInfo,
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorProfessionalInfo()
    {
        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve professional info for the doctor
        $professionalInfo = DoctorProfessionalInfo::where('doctor_id', $user->id)->first();

        if (!$professionalInfo) {
            return response()->json(['message' => 'No professional info found for this doctor'], 404);
        }

        return response()->json(['data' => $professionalInfo], 200);
    }
}
