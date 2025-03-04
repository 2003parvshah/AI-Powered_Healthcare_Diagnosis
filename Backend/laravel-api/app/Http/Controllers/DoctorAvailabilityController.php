<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorAvailability;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;


class DoctorAvailabilityController extends Controller
{
    // ✅ Store (Set Data)
    public function setDoctorAvailability(Request $request)
    {
        // Validate incoming data
        $request->validate([
            // 'doctor_id' => 'required|exists:doctors,id',
            // 'consultation_hours' => 'required|string',
            // 'online_consultation_availability' => 'required|boolean',
            // 'walk_in_availability' => 'required|boolean',
            // 'appointment_booking_required' => 'required|boolean',
            // 'time_of_one_appointment' => 'required|integer',
        ]);

        // Create or update doctor availability
        $user = JWTAuth::parseToken()->authenticate();
        $availability = DoctorAvailability::updateOrCreate(
            ['doctor_id' => $user->id], // Find by doctor_id
            $request->all() // Update with new data
        );

        return response()->json([
            'message' => 'Doctor availability updated successfully!',
            'data' => $availability,
        ], 201);
    }

    // ✅ Fetch (Get Data)
    public function getDoctorAvailability()
    {
        // Retrieve availability for the given doctor
        $user = JWTAuth::parseToken()->authenticate();

        $availability = DoctorAvailability::where('doctor_id', $user->id)->first();

        if (!$availability) {
            return response()->json(['message' => 'No availability found for this doctor'], 404);
        }

        return response()->json(['data' => $availability], 200);
    }
}
