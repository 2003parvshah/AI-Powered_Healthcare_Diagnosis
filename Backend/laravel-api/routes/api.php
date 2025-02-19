<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDoctor;
use App\Http\Middleware\IsUser;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('/hi', function () {
    return response()->json(['message' => 'hi']);
});


Route::get('/sessions', [SessionController::class, 'index']); 
Route::get('/sessions/{id}', [SessionController::class, 'show']); 
Route::delete('/sessions/{id}', [SessionController::class, 'destroy']); 
Route::delete('/sessions/user/{user_id}', [SessionController::class, 'logoutUser']); 

Route::middleware('jwt.auth')->group(function () {
    // Routes that can be accessed by any authenticated user
    Route::get('profile', [AuthController::class, 'profile']);  // Example route to show user profile

    // Admin-only routes
    Route::middleware('isAdmin')->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);  // Admin dashboard
    });

    // Doctor-only routes
    Route::middleware('isDoctor')->group(function () {
        Route::get('doctor/dashboard', [DoctorController::class, 'dashboard']);  // Doctor dashboard
    });

    // Normal user-only routes
    Route::middleware('isUser')->group(function () {
        Route::get('user/dashboard', [UserController::class, 'dashboard']);  // User dashboard
    });
});
