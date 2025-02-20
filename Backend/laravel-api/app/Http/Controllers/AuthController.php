<?php

namespace App\Http\Controllers;
use App\Http\helper\mail;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Session;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\RegisterUserRequest;   // validations file for register 
use App\Http\Requests\LoginUserRequest;   // validations file fo login
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
   
public function register(RegisterUserRequest $request)
{
    // Validation is automatically handled by RegisterUserRequest

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    if ($request->role === 'doctor') {
        Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $request->specialization_id,
            'degree_id' => $request->degree_id,
            'license_number' => $request->license_number,
        ]);
    } elseif ($request->role === 'patient') {
        Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'medical_history' => $request->medical_history,
        ]);
    }

    $token = JWTAuth::fromUser($user);

    Session::create([
        'user_id' => $user->id,
        'ip_address' => request()->ip(),
        'user_agent' => request()->header('User-Agent'),
        'payload' => json_encode([]),
        'last_activity' => now()->timestamp,
    ]);

    // Send email notification
    $subject = 'New User Registered';
    $message = '<h1>Hello, ' . $request->email . ' has registered in your app</h1>';
    mail::sendmail("admin@example.com", $subject, $message);

    return response()->json(['user' => $user, 'token' => $token], 201);
}
    /**
     * Login user
     */
    
public function login(LoginUserRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();

    Session::create([
        'user_id' => $user->id,
        'ip_address' => request()->ip(),
        'user_agent' => request()->header('User-Agent'),
        'payload' => json_encode(['token' => $token]),
        'last_activity' => now()->timestamp,
    ]);

    return response()->json(['token' => $token, 'user' => $user]);
}

    /**
     * Logout from all devices
     */
    public function logout_all()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Session::where('user_id', $user->id)->delete();
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully from all devices']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }

    /**
     * Logout from the current device
     */
    public function logout(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();

            Session::where('user_id', $user->id)
                ->where('ip_address', $request->ip())
                ->where('user_agent', $request->header('User-Agent'))
                ->delete();

            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Logged out successfully from this device']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }
}
