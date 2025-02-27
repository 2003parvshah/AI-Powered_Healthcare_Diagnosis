<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\HealthIssueController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Http\Controllers\DoctorContactInfoController;
use App\Http\Controllers\DoctorFeesController;
use App\Http\Controllers\DoctorPersonalInfoController;
use App\Http\Controllers\DoctorProfessionalInfoController;
use App\Http\Controllers\DoctorWorkExperienceController;
use App\Http\Controllers\DoctorDetailsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Doctor_timeTableController;


use App\Http\Controllers\FileController;

use App\Http\helper\cloudinaryClass;





use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDoctor;
use App\Http\Middleware\IsPatient;
use Illuminate\Support\Facades\Validator; // Add this if using manual validation




use Illuminate\Support\Facades\Route;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use Illuminate\Support\Facades\Storage;

Route::get('/test-s3', function () {
    return Storage::disk(config('filesystems.default'))->exists('wpaanetproject.pdf')
        ? 'File exists in S3!'
        : 'File not found in S3!';
});



Route::post('/upload-file', [FileController::class, 'uploadPdf']);


Route::get('/checkuploadstatus/{batchId}', function ($batchId) {
    return cloudinaryClass::checkProcessingStatus($batchId);
});

Route::get('/getpdf/{batchId}', function ($batchId) {
    return cloudinaryClass::getPdf($batchId);
});


Route::post('/upload', function (Request $request) {
    // Check if a file was uploaded
    if (!$request->hasFile('profile_photo')) {
        return response()->json([
            'message' => 'No file uploaded',
        ], 400);
    }

    $file = $request->file('profile_photo');

    // Process PDF using Cloudinary
    $uploadResponse = CloudinaryClass::splitPdf($file);
    $uploadData = json_decode($uploadResponse->getContent(), true);

    // Extract secure URL if available
    $pdfUrl = $uploadData['pdf_details']['secure_url'] ?? null;

    return response()->json([
        'message' => 'Patient record updated successfully!',
        'pdf_info' => $uploadData['pdf_details'] ?? [],
        'explode_data' => $uploadData['explode_data'] ?? [],
        'resource_info' => $uploadData['resource_info'] ?? [],
        'pdf_url' => $pdfUrl
    ], 200);
});

Route::get('/hi', function () {
    return response()->json(['message' => 'hi']);
});

Route::get('/debug', function () {
    return env('CLOUDINARY_URL');
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('logout_all', [AuthController::class, 'logout_all']);
Route::post('profile', [AuthController::class, 'profile']);
Route::post('generateOtp', [AuthController::class, 'generateOtp']);
Route::post('verifyOtp', [AuthController::class, 'verifyOtp']);
Route::post('showAllSession', [AuthController::class, 'showAllSession']);

// Route::get('/sessions', [SessionController::class, 'index']); 
// Route::get('/sessions/{id}', [SessionController::class, 'show']); 
// Route::delete('/sessions/{id}', [SessionController::class, 'destroy']); 
// Route::delete('/sessions/user/{user_id}', [SessionController::class, 'logoutUser']); 

Route::middleware('jwt.auth')->group(function () {
    // Routes that can be accessed by any authenticated user
    Route::get('profile', [AuthController::class, 'profile']);  // Example route to show user profile

    // Admin-only routes
    Route::middleware('isAdmin')->group(function () {
        // Route::get('admin/dashboard', [AdminController::class, 'dashboard']);  // Admin dashboard
    });

    // Doctor-only routes
    Route::middleware('auth:api', 'isDoctor')->prefix('doctor')->group(function () {
        // Route::get('dashboard', [DoctorController::class, 'dashboard']);  // Doctor dashboard
        Route::post('setDoctorAvailability', [DoctorAvailabilityController::class, 'setDoctorAvailability']);
        Route::get('getDoctorAvailability', [DoctorAvailabilityController::class, 'getDoctorAvailability']);
        Route::post('setDoctorContactInfo', [DoctorContactInfoController::class, 'setDoctorContactInfo']);
        Route::get('getDoctorContactInfo', [DoctorContactInfoController::class, 'getDoctorContactInfo']);
        Route::post('setDoctorFees', [DoctorFeesController::class, 'setDoctorFees']);
        Route::get('getDoctorFees', [DoctorFeesController::class, 'getDoctorFees']);
        Route::post('setDoctorPersonalInfo', [DoctorPersonalInfoController::class, 'setDoctorPersonalInfo']);
        Route::get('getDoctorPersonalInfo', [DoctorPersonalInfoController::class, 'getDoctorPersonalInfo']);
        Route::post('setDoctorProfessionalInfo', [DoctorProfessionalInfoController::class, 'setDoctorProfessionalInfo']);
        Route::get('getDoctorProfessionalInfo', [DoctorProfessionalInfoController::class, 'getDoctorProfessionalInfo']);
        Route::post('setDoctorWorkExperience', [DoctorWorkExperienceController::class, 'setDoctorWorkExperience']);
        Route::get('getDoctorWorkExperience', [DoctorWorkExperienceController::class, 'getDoctorWorkExperience']);
        Route::get('getAllInfoDoctors', [DoctorController::class, 'getAllInfoDoctors']);
        Route::post('setTimings', [Doctor_timeTableController::class, 'setTimings']); // Store doctor's timings
        Route::get('getTimings', [Doctor_timeTableController::class, 'getTimings']); // Get doctor's timin
        Route::get('getAppointments', [AppointmentController::class, 'getAppointments']);
    });

    // patient-only routes
    Route::middleware('auth:api', 'isPatient')->prefix('patient')->group(function () {
        Route::post('profile', [PatientController::class, 'profile']);  // patient profile
        Route::post('setprofile', [PatientController::class, 'setprofile']);  // patient set profile
        // Route::get('user/dashboard', [UserController::class, 'dashboard']);  
        Route::post('/addHealthIssue', [HealthIssueController::class, 'addHealthIssue']);
        Route::post('getdoctors_timetable', [HealthIssueController::class, 'getdoctors_timetable']);
        // Route::get('/health-issues/{patientId}', [HealthIssueController::class, 'getPatientHealthIssues']);
        Route::get('getAllDoctorDetails', [DoctorDetailsController::class, 'getAllDoctorDetails']);
        Route::post('setAppointment', [AppointmentController::class, 'setAppointment']);
        Route::post('getAllInfoDoctors', [DoctorController::class, 'getAllInfoDoctors']);
    });
});
