<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Patient;
use App\Http\helper\cloudinaryClass;

class PatientController extends Controller
{
    // $user = JWTAuth::parseToken()->authenticate();
    //
    public function profile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        // $patient = Patient :: where("id"  , '=' ,  $user->id);
        $patient = Patient::find($user->id);
        return response()->json($patient);
    }


    public function setprofile(Request $request)
{
        // Validate incoming request
        // $request->validate([
        //     'date_of_birth' => 'required|date',
        //     'gender' => 'required|in:male,female,other',
        //     'medical_history' => 'nullable|string',
        //     'phone_number' => 'required|string|max:15',
        //     'address' => 'required|string',
        //     'past_medical_conditions' => 'nullable|string',
        //     'allergies' => 'nullable|string',
        //     'blood_pressure' => 'nullable|string',
        //     'weight' => 'nullable|numeric',
        //     'blood_group' => 'nullable|string|max:5',
        // ]);
        $user = JWTAuth::parseToken()->authenticate();
        $patient = Patient::find($user->id);

        if ($request->hasFile('profile_photo')) {
            $image = $request->file('profile_photo');

            // Upload image to Cloudinary and get URL
            $uploadResponse = cloudinaryClass::uploadimg($image);
            $uploadData = json_decode($uploadResponse->getContent(), true);

            if (isset($uploadData['url'])) {
                $patient->profile_photo = $uploadData['url'];
            }
        }


       
     
        $patient->fill($request->all());
        $patient->profile_photo = $uploadData['url'];
        $patient->save();


        return response()->json([
            'message' => 'Patient record updated successfully!',
            'data' => $patient,
            'img' =>  $uploadData['url']
        ], 200);
    }
}
