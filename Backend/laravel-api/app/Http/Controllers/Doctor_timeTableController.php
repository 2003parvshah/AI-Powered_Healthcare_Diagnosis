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
    // public function setTimings(Request $request)
    // {
    //     $user = JWTAuth::parseToken()->authenticate();
    //     $doctorId = $user->id;

    //     $request->validate([
    //         'timings' => 'required|array',
    //         // 'timings.*.day' => 'required|string|exists:weeks,name',
    //         'timings.*.start_time' => 'required|string',
    //         'timings.*.end_time' => 'required|string',
    //         'timings.*.location' => 'required|string',
    //     ]);

    //     $timings = $request->input('timings');

    //     foreach ($timings as $timing) {
    //         doctor_timeTable::create([
    //             'doctor_id' => $doctorId,
    //             'day' => $timing['day'],
    //             'start_time' => $timing['start_time'],
    //             'end_time' => $timing['end_time'],
    //             'address' => $timing['location'],
    //             // 'timezone'
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'Timings added successfully',
    //     ], 201);
    // }
    public function setTimings(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $doctorId = $user->id;

        $request->validate([
            'timings' => 'required|array',
            'timings.*.day' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'timings.*.start_time' => 'required|date_format:H:i',
            'timings.*.end_time' => 'required|date_format:H:i|after:timings.*.start_time',
            'timings.*.location' => 'required|string',
        ]);

        $timezone = $request->header('Timezone', 'Asia/Kolkata'); // Default to IST if not provided

        if (!in_array($timezone, timezone_identifiers_list())) {
            return response()->json(['error' => 'Invalid timezone'], 400);
        }

        $timings = $request->input('timings');

        foreach ($timings as $timing) {
            // $startTimeUTC = Carbon::createFromFormat('H:i', $timing['start_time'], $timezone)->utc();/
            // $endTimeUTC = Carbon::createFromFormat('H:i', $timing['end_time'], $timezone)->utc();

            Doctor_timeTable::create([
                'doctor_id' => $doctorId,
                'day' => $timing['day'],
                'start_time' => $timing['start_time'],
                'end_time' => $timing['end_time'],
                'timezone' => $timezone,
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

        $timings = doctor_timeTable::where('doctor_id', $doctorId)->get();

        return response()->json([
            'doctor_id' => $doctorId,
            'timings' => $timings
        ], 200);
    }
}
