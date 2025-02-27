<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorPersonalInfo;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\helper\cloudinaryClass; // Import the Cloudinary Helper

class DoctorPersonalInfoController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorPersonalInfo(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
            'nationality' => 'required|string|max:100',
            'languages_spoken' => 'required|string',
        ]);

        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Handle image upload if provided
        if ($request->hasFile('profile_photo')) {
            $uploadData = cloudinaryClass::uploadimg($request->file('profile_photo'));
            $profilePhotoUrl = $uploadData->original['url'] ?? null;
        }

        // Create or update personal info record
        $personalInfo = DoctorPersonalInfo::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            [
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'profile_photo' => $profilePhotoUrl ?? null, // Store image URL
                'nationality' => $request->nationality,
                'languages_spoken' => $request->languages_spoken,
            ]
        );

        return response()->json([
            'message' => 'Doctor personal info updated successfully!',
            'data' => $personalInfo,
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorPersonalInfo()
    {
        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve personal info for the doctor
        $personalInfo = DoctorPersonalInfo::where('doctor_id', $user->id)->first();

        if (!$personalInfo) {
            return response()->json(['message' => 'No personal info found for this doctor'], 404);
        }

        return response()->json(['data' => $personalInfo], 200);
    }
}
