<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class DoctorPatientController extends Controller
{
    public function getPatientWithHealthIssues(Request $request)
    {
        // Fetch patient with health issues using Eloquent ORM
        $patient = Patient::with('healthIssues')->find($request->id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json([
            'message' => 'Patient details fetched successfully',
            'patient' => $patient
        ]);
    }
}
