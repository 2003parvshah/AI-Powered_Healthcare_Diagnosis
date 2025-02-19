<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Session;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,doctor,admin',
            'profile_photo_path' => 'nullable|string',
           'specialization' => 'nullable|string', // Only for doctors
        'license_number' => 'nullable|string', // Only for doctors
        'bio' => 'nullable|string', // Only for doctors
        'date_of_birth' => 'nullable|date', // Only for patients
        'gender' => 'nullable|string|in:male,female,other', // Only for patients
        'medical_history' => 'nullable|string', // Only for patients
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Create user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_photo_path' => $request->profile_photo_path,
            'doctor_id' => $request->role === 'doctor' ? null : null, // Initially null
            'patient_id' => $request->role === 'user' ? null : null, // Initially null
        ]);
        if ($request->role === 'doctor') {
            $user->doctor_id = $user->id;
            Doctor::create([
                'doctor_id' => $user->id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'bio' => $request->bio,
            ]);
        } elseif ($request->role === 'user') {
            $user->patient_id = $user->id;
            Patient::create([
                'patient_id' => $user->id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'medical_history' => $request->medical_history,
            ]);
        }
    
        $user->save(); // Save the updated values
    
        // Generate JWT token
        $token = JWTAuth::fromUser($user);
    
        // Store session in database
        Session::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'payload' => json_encode([]), // No session data yet
            'last_activity' => now()->timestamp,
        ]);
    
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    

    /**
     * Login and get JWT token
     */
    public function login(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get authenticated user
        $user = Auth::user();

        // Delete previous session(s) for this user (optional)
        Session::where('user_id', $user->id)->delete();

        // Store new session in the database
        Session::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'payload' => json_encode(['token' => $token]), // Store the token in session
            'last_activity' => now()->timestamp,
        ]);

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout and invalidate JWT token
     */
    public function logout_all()
    {
        try {
            // Authenticate user from token
            $user = JWTAuth::parseToken()->authenticate();

            // Delete session record for user
            // to logout in all system 
            Session::where('user_id', $user->id)->delete();

            // Invalidate JWT token
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }

    public function logout(Request $request)
{
    try {
        // Authenticate user from token
        $user = JWTAuth::parseToken()->authenticate();

        // Get current user's token
        $token = JWTAuth::getToken();

        // Find and delete the session for this specific device
        Session::where('user_id', $user->id)
            ->where('ip_address', $request->ip())
            ->where('user_agent', $request->header('User-Agent'))
            ->delete();

        // Invalidate JWT token
        JWTAuth::invalidate($token);

        return response()->json(['message' => 'Logged out successfully from this device']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to logout'], 500);
    }
}


}
