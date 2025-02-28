<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\HealthIssue;
use App\Http\helper\FileHelper;
use App\Http\helper\cloudinaryClass;
use App\Models\Appointment;
use App\Models\Doctor_timeTable;
use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\DTOs\DoctorScheduleDTO;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HealthIssueController extends Controller
{
    /**
     * Store a new health issue record
     */
    public function addHealthIssue(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string',
            'report_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'report_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'doctor_type' => 'required|string',
            'diagnosis' => 'nullable|string',
            'solution' => 'nullable|string',
            'other_info' => 'nullable|string',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        // Handle PDF upload
        $reportPdf = $request->file('report_pdf');
        $pdfstatus = $reportPdf ? FileHelper::uploadPdf($reportPdf) : null;
        $pdfData = $pdfstatus ? $pdfstatus->getData(true) : null;

        // Handle Image upload
        $reportImage = $request->file('report_image');
        $reportimagestatus = $reportImage ? cloudinaryClass::uploadimg($reportImage) : null;
        $imageData = $reportimagestatus ? $reportimagestatus->getData(true) : null;

        // Create the health issue record
        $healthIssue = HealthIssue::create([
            'patient_id' => $user->id,
            'symptoms' => $request->symptoms,
            'report_pdf' => $pdfData["url"] ?? null,  // ✅ Prevents error if $pdfData is null
            'report_image' => $imageData["url"] ?? null, // ✅ Prevents error if $imageData is null
            'doctor_type' => $request->doctor_type,
            'diagnosis' => $request->diagnosis,
            'solution' => $request->solution,
            'other_info' => $request->other_info,
        ]);

        return response()->json([
            'message' => 'Health issue recorded successfully', // ✅ Correct message
            'data' => $healthIssue, // ✅ Correct way to return created data
            'pdfdata' => $pdfData["url"] ?? null, // ✅ Prevents error if null
            'imagedata' => $imageData["url"] ?? null, // ✅ Prevents error if null
        ], 201);
    }


    public function getdoctors_timetable(Request $request)
    {
        // Authenticate doctor using JWT
        // $user = JWTAuth::parseToken()->authenticate();
        $requestedDate = Carbon::parse($request->date);
        $weekDayName = $requestedDate->format('l'); // Get the full weekday name (e.g., Monday, Tuesday)

        // Fetch all doctor schedules
        // $doctorSchedules = DoctorAvailability::where('doctor_availability.doctor_id', '=', $request->doctor_id)
        //     ->join('doctor_timeTable as tt', 'doctor_availability.doctor_id', '=', 'tt.doctor_id')
        //     ->leftJoin('appointments as ap', 'doctor_availability.doctor_id', '=', 'ap.doctor_id') // Changed to LEFT JOIN
        //     ->select([
        //         'doctor_availability.doctor_id',
        //         'doctor_availability.time_of_one_appointment',
        //         'tt.start_time',
        //         'tt.end_time',
        //         'tt.address',
        //         'tt.timezone',
        //         'ap.appointment_date'
        //     ])
        //     ->get();

        // $doctorSchedules = DoctorAvailability::where('doctor_availability.doctor_id', '=', $request->doctor_id)
        //     ->join('doctor_timeTable as tt', function ($join) use ($weekDayName) {
        //         $join->on('doctor_availability.doctor_id', '=', 'tt.doctor_id')
        //             ->where('tt.day', '=', $weekDayName); // Filter by weekday
        //     })
        //     ->leftJoin('appointments as ap', 'doctor_availability.doctor_id', '=', 'ap.doctor_id')
        //     ->select([
        //         'doctor_availability.doctor_id',
        //         'doctor_availability.time_of_one_appointment',
        //         'tt.start_time',
        //         'tt.end_time',
        //         'tt.address',
        //         'tt.timezone',
        //         'tt.day',
        //         'ap.appointment_date'
        //     ])
        //     ->get();
        $doctorSchedules = DoctorAvailability::join('doctor_timeTable as tt', 'doctor_availability.doctor_id', '=', 'tt.doctor_id')
            ->leftJoin('appointments as ap', function ($join) use ($request) {
                $join->on('doctor_availability.doctor_id', '=', 'ap.doctor_id')
                    ->where('ap.appointment_date', '=', $request->date); // Apply condition inside LEFT JOIN
            })
            ->where('doctor_availability.doctor_id', '=', $request->doctor_id)
            ->where('tt.day', '=', $weekDayName) // Apply weekday filter separately
            ->select(
                'doctor_availability.doctor_id',
                'doctor_availability.time_of_one_appointment',
                'tt.start_time',
                'tt.end_time',
                'tt.address',
                'tt.timezone',
                'tt.day',
                'ap.appointment_date'
            )
            ->get();




        // Prepare schedule data    
        $schedule = [];

        foreach ($doctorSchedules as $scheduleData) {
            // Convert start and end time to UTC
            // $startTimeUTC = Carbon::parse($scheduleData->start_time, $scheduleData->timezone)->utc();
            $startTimeUTC = Carbon::parse($request->date . ' ' . $scheduleData->start_time, $scheduleData->timezone)->utc();
            // $endTimeUTC = Carbon::parse($scheduleData->end_time, $scheduleData->timezone)->utc();
            $endTimeUTC = Carbon::parse($request->date . ' ' . $scheduleData->end_time, $scheduleData->timezone)->utc();
            Log::info('Start Time', ['local time' => $scheduleData->start_time, 'utc time' => $startTimeUTC]);


            // Convert appointments to UTC for comparison
            // $utcAppointments = Carbon::parse($scheduleData->appointment_date, $scheduleData->timezone)->utc()->toDateTimeString();

            // Generate time slots based on `time_of_one_appointment`
            $intervalMinutes = $scheduleData->time_of_one_appointment;
            $currentSlot = $startTimeUTC;

            while ($currentSlot->copy()->addMinutes($intervalMinutes)->lte($endTimeUTC)) {
                // Check if the slot matches an appointment
                $isAvailable = $currentSlot->toDateTimeString() !== $scheduleData->appointment_date;
                // dd($currentSlot->toDateTimeString());  // Print Query in Console
                // Log::info('start time', $currentSlot->toDateTimeString());
                Log::info('Start Time', ['time' => $currentSlot->toDateTimeString(), 'appointment time' => $scheduleData->appointment_date]);

                // Store data in DTO
                $scheduleDTO = new DoctorScheduleDTO(
                    $scheduleData->doctor_id,
                    $currentSlot->toDateTimeString(),
                    $isAvailable,
                    $scheduleData->address,
                    $scheduleData->timezone
                );

                $schedule[] = $scheduleDTO->toArray();

                // Move to the next slot
                $currentSlot->addMinutes($intervalMinutes);
            }
        }

        return response()->json(['schedule' => $schedule]);
    }

    /**
     * Fetch all health issues of a patient
     */
    public function getPatientHealthIssues($patientId)
    {
        $healthIssues = HealthIssue::where('patient_id', $patientId)->get();
        return response()->json($healthIssues);
    }
}
