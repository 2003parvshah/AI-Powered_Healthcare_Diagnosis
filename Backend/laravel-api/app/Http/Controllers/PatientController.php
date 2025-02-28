<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Patient;
use App\Http\helper\cloudinaryClass;
use App\Models\User;

class PatientController extends Controller
{
    // $user = JWTAuth::parseToken()->authenticate();
    //
    public function getProfile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        // $patient = Patient :: where("id"  , '=' ,  $user->id);
        $patient = Patient::find($user->id);
        $user = User::find($user->id);
        // Merge user details into patient response
        $patientData = $patient->toArray();
        $patientData['name'] = $user->name;
        $patientData['email'] = $user->email;
        $patientData['phone_number'] = $user->phone_number;
        $patientData['role'] = $user->role;
        // $patientData['created_at'] = $user->created_at;
        // $patientData['updated_at'] = $user->updated_at;

        return response()->json([
            'message' => 'Profile updated successfully!',
            'patient' => $patientData
        ], 200);
    }





    public function setProfile(Request $request)
    {
        // Authenticate user from JWT token
        $user = JWTAuth::parseToken()->authenticate();

        // Find patient record using the same ID
        $patient = Patient::where('id', $user->id)->first();
        $user = User::where('id', $user->id)->first();

        if (!$patient) {
            return response()->json(['message' => 'Patient profile not found.'], 404);
        }

        // Validate request data
        // $validatedData = $request->validate([
        //     'name' => 'nullable|string|max:255',
        //     'phone_number' => 'nullable|string|max:15|unique:users,phone_number,' . $user->id, // Unique except current user
        //     'date_of_birth' => 'nullable|date',
        //     'gender' => 'nullable|in:male,female,other',
        //     'medical_history' => 'nullable|string',
        //     'address' => 'nullable|string',
        //     'past_medical_conditions' => 'nullable|string',
        //     'allergies' => 'nullable|string',
        //     'blood_pressure' => 'nullable|string',
        //     'weight' => 'nullable|numeric',
        //     'blood_group' => 'nullable|string|max:5',
        //     'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);
        // dd($validatedData);
        // Update user details (if provided)
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
        $user->save(); // Save user updates

        // Handle profile photo upload if provided
        if ($request->hasFile('profile_photo')) {
            $image = $request->file('profile_photo');

            // Upload to Cloudinary
            $uploadResponse = cloudinaryClass::uploadimg($image);
            $uploadData = json_decode($uploadResponse->getContent(), true);

            if (isset($uploadData['url'])) {
                $patient->profile_photo = $uploadData['url'];
            }
        }

        // Update patient profile details (if provided)
        if ($request->has('date_of_birth')) {
            $patient->date_of_birth = $request->date_of_birth;
        }
        if ($request->has('gender')) {
            $patient->gender = $request->gender;
        }
        if ($request->has('medical_history')) {
            $patient->medical_history = $request->medical_history;
        }
        if ($request->has('address')) {
            $patient->address = $request->address;
        }
        if ($request->has('past_medical_conditions')) {
            $patient->past_medical_conditions = $request->past_medical_conditions;
        }
        if ($request->has('allergies')) {
            $patient->allergies = $request->allergies;
        }
        if ($request->has('blood_pressure')) {
            $patient->blood_pressure = $request->blood_pressure;
        }
        if ($request->has('weight')) {
            $patient->weight = $request->weight;
        }
        if ($request->has('blood_group')) {
            $patient->blood_group = $request->blood_group;
        }


        $patient->save(); // Save patient updates

        $patientData = $patient->toArray();
        $patientData['name'] = $user->name;
        $patientData['email'] = $user->email;
        $patientData['phone_number'] = $user->phone_number;
        $patientData['role'] = $user->role;
        // $patientData['created_at'] = $user->created_at;
        // $patientData['updated_at'] = $user->updated_at;

        return response()->json([
            'message' => 'Profile updated successfully!',
            'patient' => $patientData
        ], 200);
    }
}
