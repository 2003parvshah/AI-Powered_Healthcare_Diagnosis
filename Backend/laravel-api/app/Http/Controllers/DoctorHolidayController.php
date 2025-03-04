<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\DoctorHoliday;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

use App\Http\helper\mail;
use App\Models\User;
use Carbon\Carbon;

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
    // public function setHoliday(Request $request)
    // {
    //     // Get authenticated doctor
    //     $doctor = Auth::user();

    //     if ($doctor->role !== 'doctor') {
    //         return response()->json(['message' => 'Unauthorized. Only doctors can set holidays.'], 403);
    //     }

    //     $request->validate([
    //         'start_date' => 'required|date_format:Y-m-d H:i:s',
    //         'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
    //         'timezone' => 'required|string',
    //     ]);

    //     // Check for conflicting appointments
    //     // $conflictingAppointments = Appointment::where('doctor_id', $doctor->id)
    //     //     ->whereBetween('appointment_date', [$request->start_date, $request->end_date])
    //     //     ->pluck('health_issues_id', 'appointment_date'); // Fetch only health issue IDs

    //     $conflictingAppointments = Appointment::join('health_issues as hi', 'appointments.health_issues_id', '=', 'hi.id')
    //         ->join('users as u', 'hi.patient_id', '=', 'u.id') // Join users table to get patient details
    //         ->where('appointments.doctor_id', $doctor->id)
    //         ->whereBetween('appointments.appointment_date', [$request->start_date, $request->end_date])
    //         ->select(
    //             'appointments.appointment_date',
    //             'hi.patient_id',
    //             'u.name as patient_name',
    //             'u.email as patient_email',
    //             'appointments.health_issues_id'
    //         )
    //         ->get();



    //     if ($conflictingAppointments->isNotEmpty()) {
    //         return response()->json([
    //             'message' => 'Cannot set holiday. Appointments are already scheduled during this time.',
    //             'conflicting_health_issues' => $conflictingAppointments
    //         ], 409); // 409 Conflict HTTP status
    //     }

    //     // If no conflicting appointments, create holiday
    //     $holiday = DoctorHoliday::create([
    //         'doctor_id' => $doctor->id,
    //         'start_date' => $request->start_date,
    //         'end_date' => $request->end_date,
    //         'timezone' => $request->timezone,
    //     ]);

    //     return response()->json([
    //         'message' => 'Holiday set successfully',
    //         'holiday' => $holiday
    //     ], 201);
    // }


    // use App\Http\helper\mail;
    // use App\Models\Appointment;
    // use App\Models\User;
    // use App\Models\DoctorHoliday;
    // use Illuminate\Http\Request;
    // use Illuminate\Support\Facades\Auth;

    public function setHoliday(Request $request)
    {
        // Get authenticated doctor
        $doctor = Auth::user();
        $start = Carbon::parse($request->start_date, "Asia/Kolkata")->utc();
        $end = Carbon::parse($request->end_date, "Asia/Kolkata")->utc();

        if ($doctor->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized. Only doctors can set holidays.'], 403);
        }

        $request->validate([
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'timezone' => 'required|string',
        ]);

        // Fetch conflicting appointments with patient details
        $conflictingAppointments = Appointment::join('health_issues as hi', 'appointments.health_issues_id', '=', 'hi.id')
            ->join('users as u', 'hi.patient_id', '=', 'u.id') // Join users table to get patient details
            ->where('appointments.doctor_id', $doctor->id)
            ->whereBetween('appointments.appointment_date', [$start, $end])
            ->select(
                'appointments.id as appointment_id',
                'appointments.appointment_date',
                'hi.patient_id',
                'u.name as patient_name',
                'u.email as patient_email',
                'appointments.health_issues_id'
            )
            ->get();

        if ($conflictingAppointments->isNotEmpty()) {
            foreach ($conflictingAppointments as $appointment) {
                $to = $appointment->patient_email;
                $subject = "Appointment Cancellation Notice - Doctor Unavailable";
                $message = "
                <h3>Dear {$appointment->patient_name},</h3>
                <p>Your appointment with <strong>Dr. {$doctor->name}</strong> on <strong>{$appointment->appointment_date}</strong> 
                has been <strong>canceled</strong> because the doctor has scheduled a holiday from 
                <strong>{$start}</strong> to <strong>{$end}</strong>.</p>
                <p>We apologize for the inconvenience. Please reschedule your appointment at your convenience.</p>
                <p>Best Regards, <br> Your Healthcare Team</p>
            ";

                // Send email notification
                mail::sendmail($to, $subject, $message);

                // Delete the appointment
                Appointment::where('id', $appointment->appointment_id)->delete();
            }
        }

        // Create the holiday entry in the database
        $holiday = DoctorHoliday::create([
            'doctor_id' => $doctor->id,
            'start_date' => $start,
            'end_date' => $end,
            'timezone' => $request->timezone,
        ]);

        return response()->json([
            'message' => 'Holiday set successfully. Conflicting appointments have been canceled, and emails have been sent to affected patients.',
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
