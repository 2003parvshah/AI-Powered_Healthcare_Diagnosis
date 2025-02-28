<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\DoctorHoliday;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class DoctorHolidayController extends Controller
{
    // Middleware to require authentication
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    /**
     * Set Holiday for the Authenticated Doctor
     */
    public function setHoliday(Request $request)
    {
        // Get authenticated doctor
        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized. Only doctors can set holidays.'], 403);
        }

        $request->validate([
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'timezone' => 'required|string',
        ]);

        // Check for conflicting appointments
        $conflictingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$request->start_date, $request->end_date])
            ->pluck('health_issues_id', 'appointment_date'); // Fetch only health issue IDs

        if ($conflictingAppointments->isNotEmpty()) {
            return response()->json([
                'message' => 'Cannot set holiday. Appointments are already scheduled during this time.',
                'conflicting_health_issues' => $conflictingAppointments
            ], 409); // 409 Conflict HTTP status
        }

        // If no conflicting appointments, create holiday
        $holiday = DoctorHoliday::create([
            'doctor_id' => $doctor->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'timezone' => $request->timezone,
        ]);

        return response()->json([
            'message' => 'Holiday set successfully',
            'holiday' => $holiday
        ], 201);
    }


    /**
     * Get Holidays for the Authenticated Doctor
     */
    public function getHoliday()
    {
        // Get authenticated doctor
        $doctor = Auth::user();

        if ($doctor->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized. Only doctors can view holidays.'], 403);
        }

        $holidays = DoctorHoliday::where('doctor_id', $doctor->id)->get();

        return response()->json([
            'message' => 'Holidays fetched successfully',
            'holidays' => $holidays
        ], 200);
    }
}
