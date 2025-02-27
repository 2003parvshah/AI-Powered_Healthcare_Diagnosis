<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorContactInfo;
use Tymon\JWTAuth\Facades\JWTAuth;

class DoctorContactInfoController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorContactInfo(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'primary_phone_number' => 'required|string|max:15',
            'home_address' => 'required|string',
            'clinic_hospital_address' => 'required|string',
        ]);

        // Authenticate user (doctor)
        $user = JWTAuth::parseToken()->authenticate();

        // Create or update doctor's contact information
        $contactInfo = DoctorContactInfo::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            $request->all() // Update with new data
        );

        return response()->json([
            'message' => 'Doctor contact information updated successfully!',
            'data' => $contactInfo,
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorContactInfo()
    {
        // Authenticate user (doctor)
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve contact info for the given doctor
        $contactInfo = DoctorContactInfo::where('doctor_id', $user->id)->first();

        if (!$contactInfo) {
            return response()->json(['message' => 'No contact information found for this doctor'], 404);
        }

        return response()->json(['data' => $contactInfo], 200);
    }
}
