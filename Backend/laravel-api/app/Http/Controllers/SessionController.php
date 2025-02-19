<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\User;

class SessionController extends Controller
{
    // List all active sessions
    public function index()
    {
        return response()->json(Session::all());
    }

    // Show a specific session by ID
    public function show($id)
    {
        $session = Session::find($id);
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        return response()->json($session);
    }

    // Remote logout (delete a session by ID)
    public function destroy($id)
    {
        $session = Session::find($id);
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        $session->delete();
        return response()->json(['message' => 'Session deleted successfully']);
    }

    // Remote logout by user ID (log out all sessions for a user)
    public function logoutUser($user_id)
    {
        $sessions = Session::where('user_id', $user_id)->delete();
        return response()->json(['message' => 'All sessions for user logged out successfully']);
    }
}
