<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorPersonalInfo;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\helper\cloudinaryClass; // Import the Cloudinary Helper
use App\Models\Doctor;
use App\Models\User;

class DoctorPersonalInfoController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorPersonalInfo(Request $request)
    {

        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();
        $doctor = Doctor::where('id', $user->id)->first();
        $user = User::where('id', $user->id)->first();
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
        $user->save(); // Save user updatesif ($request->hasFile('profile_photo')) {
        // $image = $request->file('profile_photo');



        if ($request->has('specialization')) {
            $doctor->specialization = $request->specialization;
        }
        if ($request->has('degree')) {
            $doctor->degree = $request->degree;
        }
        if ($request->has('medical_history')) {
            $doctor->license_number = $request->license_number;
        }

        $doctor->save(); // Save patient updates

        // Create or update personal info record
        if ($request->hasFile('profile_photo')) {
            $image = $request->file('profile_photo');

            // Get current doctor's existing personal info (if any)
            $personalInfo = DoctorPersonalInfo::where('doctor_id', $user->id)->first();
            $oldProfilePhoto = $personalInfo->profile_photo ?? null;

            // Upload new image to Cloudinary
            $uploadResponse = cloudinaryClass::uploadimg($image);
            $uploadData = json_decode($uploadResponse->getContent(), true) ?? [];

            if (isset($uploadData['url'])) {
                // Delete the old profile photo if it exists
                if ($oldProfilePhoto) {
                    cloudinaryClass::deleteByUrl($oldProfilePhoto);
                }
            }
        }

        // Determine the profile photo (keep existing if no new upload)
        $profilePhoto = $uploadData['url'] ?? ($personalInfo->profile_photo ?? null);

        // Update or create doctor personal info
        $personalInfo = DoctorPersonalInfo::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            [
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'profile_photo' => $profilePhoto, // Store new image or keep existing one
                'nationality' => $request->nationality,
                'languages_spoken' => $request->languages_spoken,
            ]
        );


        return response()->json([
            'message' => 'Doctor personal info updated successfully!',
            'data' => [
                'id' => $personalInfo->id,
                'doctor_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                // 'otp' => $user->otp,
                'date_of_birth' => $personalInfo->date_of_birth,
                'gender' => $personalInfo->gender,
                'profile_photo' => $personalInfo->profile_photo,
                'nationality' => $personalInfo->nationality,
                'languages_spoken' => $personalInfo->languages_spoken,
                'specialization' => $doctor->specialization,
                'degree' => $doctor->degree,
                'license_number' => $doctor->license_number,
                // 'created_at' => $doctor->created_at,
                // 'updated_at' => $personalInfo->updated_at
            ]
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorPersonalInfo()
    {
        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();
        $doctor = Doctor::where('id', $user->id)->first();
        $user = User::where('id', $user->id)->first();
        // Retrieve personal info for the doctor
        $personalInfo = DoctorPersonalInfo::where('doctor_id', $user->id)->first();

        if (!$personalInfo) {
            return response()->json(['message' => 'No personal info found for this doctor'], 404);
        }

        // return response()->json(['data' => $personalInfo], 200);

        return response()->json([
            'message' => 'Doctor personal info updated successfully!',
            'data' => [
                'id' => $personalInfo->id,
                'doctor_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'otp' => $user->otp,
                'date_of_birth' => $personalInfo->date_of_birth,
                'gender' => $personalInfo->gender,
                'profile_photo' => $personalInfo->profile_photo,
                'nationality' => $personalInfo->nationality,
                'languages_spoken' => $personalInfo->languages_spoken,
                'specialization' => $doctor->specialization,
                'degree' => $doctor->degree,
                'license_number' => $doctor->license_number,
                'created_at' => $doctor->created_at,
                'updated_at' => $personalInfo->updated_at
            ]
        ], 201);
    }
}
