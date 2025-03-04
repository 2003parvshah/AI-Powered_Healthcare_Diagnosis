<?php

namespace App\Http\helper;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\ApiClient;
// use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Exception\ApiError;

use Illuminate\Support\Facades\Log;
// use App\Http\helper\cloudinaryClass;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
class cloudinaryClass
{

    public static function getPdf($batchId)
    {
        try {
            $cloudinary = new AdminApi();

            // Get the status of the batch process
            $response = $cloudinary->assetsByAssetIds([$batchId]);

            // Log the response
            Log::info('Cloudinary Get PDF Response:', $response->getArrayCopy());

            return response()->json([
                'message' => 'PDF retrieval successful',
                'data' => $response->getArrayCopy()
            ], 200);
        } catch (ApiError $e) {
            return response()->json([
                'error' => 'Failed to retrieve PDF',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public static function checkProcessingStatus($batchId)
    {
        try {
            $cloudinary = new AdminApi();

            // Correct API call to check batch processing status
            $response = $cloudinary->assetsByAssetIds([$batchId]);

            return response()->json($response);
        } catch (ApiError $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public static function splitPdf($file)
    {
        try {
            // Upload the PDF to Cloudinary
            $uploadResponse = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'pdfs',
                'resource_type' => 'auto', // Automatically detect file type
                'format' => 'pdf',
            ]);

            // Convert ApiResponse to an array for logging
            // Log::info('Cloudinary Upload Response:', $uploadResponse->getArrayCopy());

            // Ensure a secure URL exists
            $secureUrl = $uploadResponse['secure_url'] ?? null;
            if (!$secureUrl) {
                return response()->json([
                    'error' => 'Cloudinary upload failed. No Secure URL returned.',
                    'response' => $uploadResponse->getArrayCopy()
                ], 500);
            }

            $publicId = $uploadResponse['public_id'];

            // Extract all pages using Cloudinary's explode API
            $explodeResponse = Cloudinary::uploadApi()->explode($publicId, [
                'page' => 'all'
            ]);

            // Log explode response
            // Log::info('Cloudinary Explode Response:', $explodeResponse->getArrayCopy());

            // Fetch full details about the uploaded PDF using AdminApi
            $adminApi = new AdminApi(); // Properly initialize AdminApi
            $resourceResponse = $adminApi->asset($publicId, [
                'resource_type' => 'image'
            ]);

            // Log the resource details
            // Log::info('Cloudinary Resource Info:', $resourceResponse);

            return response()->json([
                'message' => 'PDF uploaded and processed successfully',
                'pdf_details' => [
                    'public_id' => $publicId,
                    'secure_url' => $secureUrl,
                    'original_filename' => $uploadResponse['original_filename'] ?? null,
                    'format' => $uploadResponse['format'] ?? 'pdf',
                    'pages' => $uploadResponse['pages'] ?? 1,
                    'bytes' => $uploadResponse['bytes'] ?? 0,
                    'created_at' => $uploadResponse['created_at'] ?? null,
                ],
                'explode_data' => $explodeResponse->getArrayCopy(),
                'resource_info' => $resourceResponse // Full details of the uploaded PDF
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF processing failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }




    // uploadPdf
    public static function uploadPdf($file)
    {
        try {
            // Upload PDF to Cloudinary
            $response = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'pdfs', // Folder for PDFs
                // 'resource_type' => 'auto', // Cloudinary setting for non-image files
                // 'format' => 'pdf', // Ensure the file is saved as a PDF
                // // 'flags' => 'attachment:false' // Forces download instead of inline view
                // 'flags' => ['attachment:false'], // Ensures the PDF opens in the browser
                'resource_type' => 'image', // Convert PDF pages to images
                'format' => 'jpg', // Output format for each page
                'page' => 'all' // Extract all pages
            ]);

            // Get public ID & secure URL
            return response()->json([
                'message' => 'PDF Upload successful',
                'url' => $response->getSecurePath(),
                'public_id' => $response->getPublicId()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Cloudinary PDF Upload Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Upload failed'], 500);
        }
    }

    // Upload Image
    public  static function uploadimg($file)
    {
        try {
            $uploadedFile = $file;

            // Upload to Cloudinary
            $response = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'img', // Optional folder
            ]);

            // Get public ID & secure URL
            $imageUrl = $response->getSecurePath();
            $publicId = $response->getPublicId();

            return response()->json([
                'message' => 'Upload successful',
                'url' => $imageUrl,
                'public_id' => $publicId
            ], 200);
        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Upload failed'], 500);
        }
    }

    // Fetch Image by Public ID
    public  static function getfile($publicId)
    {
        try {
            $imageUrl = Cloudinary::getUrl($publicId);
            return response()->json(['url' => $imageUrl], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching image', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Image not found'], 404);
        }
    }

    // Delete Image by Public ID
    public  static function delete($publicId)
    {
        try {
            Cloudinary::destroy($publicId);
            return response()->json(['message' => 'Image deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Cloudinary Delete Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }


    public static function deleteByUrl($imageUrl)
    {
        try {
            // Extract public ID from the Cloudinary URL
            $parsedUrl = parse_url($imageUrl, PHP_URL_PATH);
            $pathParts = explode('/', $parsedUrl);
            $fileNameWithExt = end($pathParts);
            $publicId = pathinfo($fileNameWithExt, PATHINFO_FILENAME); // Remove file extension

            // Call Cloudinary delete function
            Cloudinary::destroy($publicId);

            return response()->json(['message' => 'Image deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Cloudinary Delete Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }
}
