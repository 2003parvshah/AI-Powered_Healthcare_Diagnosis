<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\DTOs\AppointmentsDTO;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class AppointmentController extends Controller
{

    public function getAppointments()
    {
        try {
            // Get authenticated doctor
            $user = JWTAuth::parseToken()->authenticate();

            if ($user->role == "patient") {
                // ->where('u.id', $user->id)
            }
            if ($user->role == "doctor") {
                // ->where('appointments.doctor_id', $user->id)

            }

            $appointments = Appointment::join('health_issues as hi', 'appointments.health_issues_id', '=', 'hi.id')
                ->join('users as u', 'hi.patient_id', '=', 'u.id')
                ->select(
                    'appointments.id as appointment_id',
                    'appointments.appointment_date',
                    'u.name as patient_name',
                    'hi.diagnosis',
                    'u.id',
                    'appointments.doctor_id',

                )
                ->orderBy('appointments.health_issues_id', 'desc');

            // Apply filters based on user role
            if ($user->role == "patient") {
                $appointments->where('u.id', $user->id); // Get appointments for the logged-in patient
            } elseif ($user->role == "doctor") {
                $appointments->where('appointments.doctor_id', $user->id); // Get appointments for the doctor
            }

            // Execute query
            $appointments = $appointments->get();

            // Transform each appointment into a DTO
            // $appointmentDTOs = $appointments->map(function ($appointment) {
            //     return new AppointmentsDTO($appointment);
            // });

            return response()->json([
                'message' => 'Appointments fetched successfully',
                'appointments' => $appointments
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    // Store a new appointment
    public function setAppointment(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // $request->validate([
        //     'doctor_id' => 'required|exists:doctors,id',
        //     // 'patient_id' => 'required|exists:patients,id',
        //     'appointment_date' => 'required|date',
        // ]);

        $appointment = Appointment::create([
            'patient_id' => $user->id,
            'doctor_id' => $request->doctor_id,
            'health_issues_id' => $request->health_issues_id,
            'appointment_date' => Carbon::parse($request->appointment_date, $request->header('Timezone'))->utc(),


        ]);

        return response()->json([
            'message' => 'Appointment created successfully',
            'appointment' => $appointment
        ], 201);
    }
}
