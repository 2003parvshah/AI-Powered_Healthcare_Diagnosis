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
use App\Models\DoctorPersonalInfo;

class AuthController extends Controller
{

    // basic user's profile 
    public function profile(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    //   Register a new user
    // Validation is automatically handled by RegisterUserRequest
    // create user and doctor / patient(dob , gender , medical_history)  , session created 
    public function register(RegisterUserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'doctor') {
            Doctor::create([
                'id' => $user->id,
                'specialization' => $request->specialization,
                'degree' => $request->degree,
                'license_number' => $request->license_number,
            ]);
            DoctorPersonalInfo::create([
                'doctor_id' => $user->id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
            ]);
        } elseif ($request->role === 'patient') {
            Patient::create([
                'id' => $user->id,
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
        // $subject = 'New User Registered';
        // $message = '<h1>Hello, ' . $request->email . ' has registered in your app</h1>';
        // mail::sendmail("admin@example.com", $subject, $message);

        return response()->json(['user' => $user, 'token' => $token], 201);
    }











    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="patient Login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="jwt_token_here"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    // jwt , session created 
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

    // take user id and logout all it's sessions 
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

    //    take user id , ip address , user agent and logout from that pc
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

    // show all sessions 
    public function showAllSession(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Find the session for the authenticated user
        $sessions    = Session::where('user_id', $user->id)->get();

        if (!$sessions) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        // return response()->json($session);
        return response()->json($sessions);
    }

    public function generateOtp(Request $request)
    {
        // Validate the request (Ensure phone or email exists)
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Generate a random 6-digit OTP
        $otp = rand(1000, 9999);

        // Save OTP in the database
        $user->otp = $otp;
        $user->save();

        // Optionally, send OTP via email (or SMS)
        // Mail::to($user->email)->send(new OtpMail($otp));

        //  Send email notification
        $subject = 'health.AI otp';
        $message = '<h1>Hello, ' . $request->email . ' your otp is  ' . $otp . '</h1>';
        mail::sendmail($request->email, $subject, $message);


        return response()->json([
            'message' => 'OTP generated successfully',
            'otp' => $otp, // Remove this in production for security reasons
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        // Validate the request (Ensure OTP and email are provided)
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if OTP matches
        if ($user->otp == $request->otp) {
            // OTP is correct → Clear OTP after successful verification
            // $user->otp = null;
            $user->password = Hash::make($request->password);   //Hash::make
            $user->save();

            return response()->json([
                'message' => 'OTP verified successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid OTP. Please try again.',
            ], 400);
        }
    }
}
