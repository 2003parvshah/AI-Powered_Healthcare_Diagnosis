<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorFees;
use Tymon\JWTAuth\Facades\JWTAuth;

class DoctorFeesController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorFees(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'consultation_fees' => 'required|numeric',
            'payment_methods_accepted' => 'required|string',
        ]);

        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Create or update doctor fees record
        $fees = DoctorFees::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            $request->all() // Update with new data
        );

        return response()->json([
            'message' => 'Doctor fees updated successfully!',
            'data' => $fees,
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorFees()
    {
        // Authenticate user
        $user = JWTAuth::parseToken()->authenticate();

        // Retrieve fees for the doctor
        $fees = DoctorFees::where('doctor_id', $user->id)->first();

        if (!$fees) {
            return response()->json(['message' => 'No fees record found for this doctor'], 404);
        }

        return response()->json(['data' => $fees], 200);
    }
}
