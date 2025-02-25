<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDoctor;
use App\Http\Middleware\isPatient;
use Illuminate\Support\Facades\Route;

Route::get('/demo', function () {
    return response()->json([
        'message' => 'This is a demo message from the GET API without a controller!',
    ]);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::middleware('jwt.auth')->group(function () {
//     // Routes that can be accessed by any authenticated user
//     Route::get('profile', [AuthController::class, 'profile']);  // Example route to show user profile

//     // Admin-only routes
//     Route::middleware('isAdmin')->group(function () {
//         Route::get('admin/dashboard', [AdminController::class, 'dashboard']);  // Admin dashboard
//     });

//     // Doctor-only routes
//     Route::middleware('isDoctor')->group(function () {
//         Route::get('doctor/dashboard', [DoctorController::class, 'dashboard']);  // Doctor dashboard
//     });

//     // Normal user-only routes
//     Route::middleware('isUser')->group(function () {
//         Route::get('user/dashboard', [UserController::class, 'dashboard']);  // User dashboard
//     });
// });
