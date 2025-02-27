<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\DTOs\AppointmentsDTO;

class AppointmentController extends Controller
{


    public function getAppointments()
    {
        try {
            // Get authenticated doctor
            $doctor = JWTAuth::parseToken()->authenticate();

            // Fetch appointments for this doctor
            // $appointments = Appointment::join('patients as p', 'appointments.patient_id', '=', 'p.id')
            //     ->join()
            //     ->where('appointments.doctor_id', $doctor->id) // Get doctor_id from auth
            //     ->orderBy('appointments.health_issues_id', 'desc')
            //     ->select('appointments.*', 'p.*') // Select appointment and patient details
            //     ->get();
            $appointments = Appointment::join('users as p', 'appointments.patient_id', '=', 'p.id')
                ->leftJoin('health_issues as hi', 'p.id', '=', 'hi.patient_id') // Left Join with health_issues
                ->where('appointments.doctor_id', $doctor->id) // Get doctor_id from authenticated doctor
                ->orderBy('appointments.health_issues_id', 'desc')
                ->select('appointments.id', 'appointments.appointment_date', 'p.name', 'hi.diagnosis') // Select appointment, patient, and health issue details
                ->get();


            // Transform each appointment into a DTO
            // $appointmentDTOs = $appointments->map(function ($appointment) {
            //     return new AppointmentsDTO($appointment);
            // });

            return response()->json([
                'message' => 'Appointments fetched successfully',
                'appointments' => $appointments
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch appointments'], 500);
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
