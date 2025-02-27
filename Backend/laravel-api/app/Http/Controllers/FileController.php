<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function uploadPdf(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'file' => 'required|mimes:pdf|max:51200', // Allow only PDFs up to 50MB
            ]);

            $file = $request->file('file');
            $filePath = 'pdfs/' . time() . '_' . $file->getClientOriginalName(); // Store in pdfs folder

            // Upload to AWS S3
            $uploadSuccess = Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

            if (!$uploadSuccess) {
                return response()->json(['error' => 'Failed to upload PDF'], 500);
            }

            // Get the S3 URL
            $url = Storage::disk('s3')->url($filePath);

            return response()->json([
                'message' => 'PDF uploaded successfully',
                'url' => $url
            ], 200);
        } catch (\Exception $e) {
            Log::error('PDF Upload Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Upload failed',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPdf()
    {
        return response()->json([
            'message' => 'PDF retrieved successfully',
            'url' => 'https://healthai.s3.us-east-2.amazonaws.com/wpaanetproject.pdf' // Updated URL with correct region
        ], 200);
    }
}
