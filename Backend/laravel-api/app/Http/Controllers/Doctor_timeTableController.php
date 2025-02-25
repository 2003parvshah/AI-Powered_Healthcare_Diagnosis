<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Doctor_timeTable;
use App\Models\Week;

class Doctor_timeTableController extends Controller
{
    /**
     * Store multiple timing records for a doctor
     */
    public function setTimings(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $doctorId = $user->id;

        $request->validate([
            'timings' => 'required|array',
            'timings.*.day' => 'required|string|exists:weeks,name',
            'timings.*.start_time' => 'required|string',
            'timings.*.end_time' => 'required|string',
            'timings.*.location' => 'required|string',
        ]);

        $timings = $request->input('timings');

        foreach ($timings as $timing) {
            Doctor_timeTable::create([
                'doctor_id' => $doctorId,
                'day' => $timing['day'],
                'start_time' => $timing['start_time'],
                'end_time' => $timing['end_time'],
                'address' => $timing['location'],
            ]);
        }

        return response()->json([
            'message' => 'Timings added successfully',
        ], 201);
    }

    /**
     * Get all timings for the authenticated doctor
     */
    public function getTimings()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $doctorId = $user->id;

        $timings = Doctor_timeTable::where('doctor_id', $doctorId)->get();

        return response()->json([
            'doctor_id' => $doctorId,
            'timings' => $timings
        ], 200);
    }
}
