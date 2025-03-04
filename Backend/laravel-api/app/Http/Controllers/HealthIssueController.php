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
use App\Models\DoctorHoliday;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;

class HealthIssueController extends Controller
{
    /**
     * Store a new health issue record
     */




    public function addHealthIssue(Request $request)
    {
        $request->validate([
            // 'symptoms' => 'required|string',
            'report_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'report_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'doctor_type' => 'required|string',
            'other_info' => 'nullable|string',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        // // Check if this health issue already exists
        // $existingIssue = HealthIssue::where('patient_id', $user->id)
        //     ->where('symptoms', $request->symptoms)
        //     ->first();

        // // If existing, return stored data
        // if ($existingIssue) {
        //     return response()->json([
        //         'message' => 'Existing health issue retrieved successfully',
        //         'data' => $existingIssue,
        //     ], 200);
        // }

        // Handle PDF upload
        $reportPdf = $request->file('report_pdf');
        $pdfstatus = $reportPdf ? FileHelper::uploadPdf($reportPdf) : null;
        $pdfData = $pdfstatus ? $pdfstatus->getData(true) : null;

        // Handle Image upload
        $reportImage = $request->file('report_image');
        $reportimagestatus = $reportImage ? cloudinaryClass::uploadimg($reportImage) : null;
        $imageData = $reportimagestatus ? $reportimagestatus->getData(true) : null;

        // API Base URLs from .env
        $textApiUrl = env('SYMPTOMS_API_URL', 'http://13.126.13.196:8000/predict');
        $imageApiUrl = env('IMAGE_API_URL', 'http://13.127.245.150:8000/predict_image');

        // Initialize diagnosis & solution
        $diagnosis = null;
        $solution = null;

        try {
            // 1. Call text-based prediction API
            $textPredictionResponse = Http::post($textApiUrl, [
                'symptoms' => $request->symptoms,
            ])->json();

            // Extract diagnosis and solution from API response
            $diagnosis = $textPredictionResponse['predicted_disease'] ?? null;
            // $solution = $textPredictionResponse['solution'] ?? null;

            // 2. Call image-based prediction API (if image exists)
            if ($reportImage) {
                $imagePredictionResponse = Http::attach(
                    'file',
                    file_get_contents($reportImage->getRealPath()),
                    $reportImage->getClientOriginalName()
                )->post("$imageApiUrl?task=" . $request->report_image_type)->json();


                // If image API returns a diagnosis or solution, use it (optional)
                // $diagnosis = $imagePredictionResponse['diagnosis'] ?? $diagnosis;
                // $solution = $imagePredictionResponse['prediction'] ?? $solution;
                $solution = json_encode($imagePredictionResponse);
            }
        } catch (\Exception $e) {
            Log::error('API Call Failed', ['error' => $e->getMessage()]);
        }

        // Create new health issue with API results
        $healthIssue = HealthIssue::create([
            'patient_id' => $user->id,
            'symptoms' => $request->symptoms,
            'report_pdf' => $pdfData["url"] ?? null,
            'report_image' => $imageData["url"] ?? null,
            'doctor_type' => $request->doctor_type,
            'diagnosis' => $diagnosis,
            'solution' => $solution,
            'other_info' => $request->other_info,
        ]);

        return response()->json([
            'message' => 'Health issue recorded successfully',
            'data' => $healthIssue,
            'd api' => $textPredictionResponse,
            'v api' => $solution,

        ], 201);
    }


    public function getPatientHealthIssues()
    {
        try {
            // Get authenticated user
            // $user = auth()->user();
            $user = JWTAuth::parseToken()->authenticate();


            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Fetch health issues for the patient
            $healthIssues = HealthIssue::where('patient_id', $user->id)->get();

            return response()->json([
                'status' => 'success',
                'data' => $healthIssues
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }



    public function getdoctors_timetable(Request $request)
    {

        $doctor_id = $request->doctor_id;
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->role == "doctor") {
            $doctor_id = $user->id;
        }
        // Authenticate doctor using JWT
        // $user = JWTAuth::parseToken()->authenticate();
        $requestedDate = Carbon::parse($request->date);
        $weekDayName = $requestedDate->format('l'); // Get the full weekday name (e.g., Monday, Tuesday)
        $targetDate = $request->date;
        $doctorHolidays = DoctorHoliday::whereDate('start_date', '<=', $targetDate)
            ->whereDate('end_date', '>=', $targetDate)
            ->where('doctor_id', '=', $doctor_id)
            ->get(['id', 'doctor_id', 'start_date', 'end_date', 'byDoctor', 'timezone', 'created_at', 'updated_at'])
            ->map(function ($holiday) use ($targetDate) {
                return [
                    // 'id' => $holiday->id,
                    // 'doctor_id' => $holiday->doctor_id,
                    'start_date' => $holiday->start_date,
                    'end_date' => $holiday->end_date,
                    // 'byDoctor' => $holiday->byDoctor,
                    // 'timezone' => $holiday->timezone,
                    // 'created_at' => $holiday->created_at,
                    // 'updated_at' => $holiday->updated_at,

                    'start_time' => ($targetDate == Carbon::parse($holiday->start_date)->format('Y-m-d'))
                        ? Carbon::parse($holiday->start_date)->format('H:i:s')
                        : '00:00:00', // Default to start of the day

                    'end_time' => ($targetDate == Carbon::parse($holiday->end_date)->format('Y-m-d'))
                        ? Carbon::parse($holiday->end_date)->format('H:i:s')
                        : '23:59:59', // Default to end of the day

                    'full_day' => (Carbon::parse($holiday->start_date)->format('Y-m-d') < $targetDate &&
                        Carbon::parse($holiday->end_date)->format('Y-m-d') > $targetDate),

                ];
            });


        $doctorSchedules = DoctorAvailability::join('doctor_timeTable as tt', 'doctor_availability.doctor_id', '=', 'tt.doctor_id')
            ->where('doctor_availability.doctor_id', '=', $doctor_id)
            ->where('tt.day', '=', $weekDayName)
            ->select(
                'doctor_availability.doctor_id',
                'doctor_availability.time_of_one_appointment',
                'tt.start_time',
                'tt.end_time',
                'tt.address',
                'tt.timezone',
                'tt.day',
                // 'ap.appointment_date'
            )
            ->get();

        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->whereDate('appointment_date', $request->date) // Ensures date comparison
            ->get();
        $bookedTimes = $appointments->map(function ($appointment) {
            return Carbon::parse($appointment->appointment_date)->format('H:i:s');
        })->toArray();





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
            // $bookedTimes = $appointments->map(function ($appointment) {
            //     return Carbon::parse($appointment->appointment_date)->format('H:i:s');
            // })->toArray();

            while ($currentSlot->copy()->addMinutes($intervalMinutes)->lte($endTimeUTC)) {
                $slotTime = Carbon::parse($currentSlot)->format('H:i:s');

                // Check if doctor is on holiday
                $doctorOnHoliday = false;
                $doctorHolidayMessage = null;
                foreach ($doctorHolidays as $holiday) {
                    if ($slotTime >= $holiday['start_time'] && $slotTime <= $holiday['end_time']) {
                        $doctorOnHoliday = true;
                        $doctorHolidayMessage = "Doctor is on holiday during this time.";
                        break;
                    }
                }

                // Determine if slot is available
                if ($doctorOnHoliday) {
                    $isAvailable = false;
                } elseif (in_array($slotTime, $bookedTimes)) {
                    $isAvailable = false; // Slot is already booked
                } else {
                    $isAvailable = true; // Slot is free
                }

                // Create schedule DTO
                $scheduleDTO = new DoctorScheduleDTO(
                    $scheduleData->doctor_id,
                    $currentSlot->toDateTimeString(),
                    $isAvailable,
                    $scheduleData->address,
                    $scheduleData->timezone
                );

                $slotData = $scheduleDTO->toArray();

                // If doctor is on holiday, include holiday details
                if ($doctorOnHoliday) {
                    $slotData['doctorHolidayMessage'] = $doctorHolidayMessage;
                    $slotData['holiday_start_time'] = $holiday['start_time'];
                    $slotData['holiday_end_time'] = $holiday['end_time'];
                }

                $schedule[] = $slotData;

                // Move to next slot
                $currentSlot->addMinutes($intervalMinutes);
            }
        }

        return response()->json(['schedule' => $schedule]);
        // return response()->json(['schedule' => $doctorHolidays]);
    }

    // public function getdoctors_timetable(Request $request)
    // {
    //     $requestedDate = Carbon::parse($request->date);
    //     $weekDayName = $requestedDate->format('l');

    //     $targetDate = $request->date;

    //     // Fetch doctor holidays
    //     $doctorHolidays = DoctorHoliday::where('doctor_id', $request->doctor_id)
    //         ->whereDate('start_date', '<=', $targetDate)
    //         ->whereDate('end_date', '>=', $targetDate)
    //         ->get()
    //         ->map(function ($holiday) use ($targetDate) {
    //             return [
    //                 'start_time' => Carbon::parse($holiday->start_date)->format('Y-m-d') == $targetDate
    //                     ? Carbon::parse($holiday->start_date)->format('H:i:s')
    //                     : '00:00:00',
    //                 'end_time' => Carbon::parse($holiday->end_date)->format('Y-m-d') == $targetDate
    //                     ? Carbon::parse($holiday->end_date)->format('H:i:s')
    //                     : '23:59:59',
    //                 'full_day' => Carbon::parse($holiday->start_date)->format('Y-m-d') < $targetDate &&
    //                     Carbon::parse($holiday->end_date)->format('Y-m-d') > $targetDate,
    //             ];
    //         });

    //     // If doctor is on full-day leave, return an empty schedule
    //     if ($doctorHolidays->contains('full_day', true)) {
    //         return response()->json(['schedule' => []]);
    //     }

    //     // Fetch doctor availability and appointments
    //     $doctorSchedules = DoctorAvailability::join('doctor_timeTable as tt', 'doctor_availability.doctor_id', '=', 'tt.doctor_id')
    //         ->where('doctor_availability.doctor_id', '=', $request->doctor_id)
    //         ->where('tt.day', '=', $weekDayName)
    //         ->select(
    //             'doctor_availability.doctor_id',
    //             'doctor_availability.time_of_one_appointment',
    //             'tt.start_time',
    //             'tt.end_time',
    //             'tt.address',
    //             'tt.timezone',
    //             'tt.day',
    //             // 'ap.appointment_date'
    //         )
    //         ->get();

    //     $appointments = Appointment::where('doctor_id', $request->doctor_id)
    //         ->whereDate('appointment_date', $request->date) // Ensures date comparison
    //         ->get();


    //     // Generate schedule
    //     $schedule = [];

    //     foreach ($doctorSchedules as $scheduleData) {
    //         $startTimeUTC = Carbon::parse($request->date . ' ' . $scheduleData->start_time, $scheduleData->timezone)->utc();
    //         $endTimeUTC = Carbon::parse($request->date . ' ' . $scheduleData->end_time, $scheduleData->timezone)->utc();
    //         $intervalMinutes = $scheduleData->time_of_one_appointment;
    //         $currentSlot = $startTimeUTC;

    //         while ($currentSlot->copy()->addMinutes($intervalMinutes)->lte($endTimeUTC)) {
    //             $slotTime = $currentSlot->format('H:i:s');

    //             // Check if doctor is on holiday during this slot
    //             $doctorOnHoliday = $doctorHolidays->contains(function ($holiday) use ($slotTime) {
    //                 return $slotTime >= $holiday['start_time'] && $slotTime <= $holiday['end_time'];
    //             });

    //             // Determine availability
    //             $isAvailable = !$doctorOnHoliday;
    //             $doctorHolidayMessage = $doctorOnHoliday ? "Doctor is on holiday during this time." : null;



    //             if (in_array($currentSlot->format('H:i:s'), $bookedTimes)) {
    //                 return response()->json([
    //                     'message' => 'Slot is already booked',
    //                     'available' => false
    //                 ], 200);
    //             } else {
    //                 return response()->json([
    //                     'message' => 'Slot is available',
    //                     'available' => true
    //                 ], 200);
    //             }



    //             // Store slot in schedule
    //             $schedule[] = [
    //                 'doctor_id' => $scheduleData->doctor_id,
    //                 'time' => $currentSlot->toDateTimeString(),
    //                 'is_available' => $isAvailable,
    //                 'address' => $scheduleData->address,
    //                 'timezone' => $scheduleData->timezone,
    //                 'doctorHolidayMessage' => $doctorHolidayMessage,
    //             ];

    //             // Move to next slot
    //             $currentSlot->addMinutes($intervalMinutes);
    //         }
    //     }

    //     return response()->json(['schedule' => $appointments]);
    // }

    /**
     * Fetch all health issues of a patient
     */
    // public function getPatientHealthIssues($patientId)
    // {
    //     $healthIssues = HealthIssue::where('patient_id', $patientId)->get();
    //     return response()->json($healthIssues);
    // }
}
