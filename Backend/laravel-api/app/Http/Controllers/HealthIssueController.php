<?php
namespace App\Http\Controllers;

use App\Models\HealthIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HealthIssueController extends Controller
{
    /**
     * Store a new health issue record
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'symptoms' => 'required|string',
            'report_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'report_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'doctor_type' => 'required|string',
            'diagnosis' => 'nullable|string',
            'solution' => 'nullable|string',
            'other_info' => 'nullable|string',
        ]);

        // Handle file uploads
        $reportPdfPath = $request->file('report_pdf') ? $request->file('report_pdf')->store('reports/pdfs', 'public') : null;
        $reportImagePath = $request->file('report_image') ? $request->file('report_image')->store('reports/images', 'public') : null;

        // Create the health issue record
        $healthIssue = HealthIssue::create([
            'patient_id' => $request->patient_id,
            'symptoms' => $request->symptoms,
            'report_pdf' => $reportPdfPath,
            'report_image' => $reportImagePath,
            'doctor_type' => $request->doctor_type,
            'diagnosis' => $request->diagnosis,
            'solution' => $request->solution,
            'other_info' => $request->other_info,
        ]);

        return response()->json(['message' => 'Health issue recorded successfully', 'data' => $healthIssue], 201);
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
