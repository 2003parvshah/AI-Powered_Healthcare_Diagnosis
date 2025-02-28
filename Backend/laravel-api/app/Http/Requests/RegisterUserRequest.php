<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to false if authorization logic is required
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:doctor,patient',
            // 'specialization_id' => 'required_if:role,doctor|exists:specializations,id',
            ' specialization' => 'required_if:role,doctor|in:Cardiology,Neurology,Dermatology,Orthopedics,Pediatrics,Gynecology,Oncology,Psychiatry,Ophthalmology,ENT,Endocrinology,Gastroenterology,Nephrology,Pulmonology,Urology,Rheumatology,Hematology,Anesthesiology,Radiology,Pathology,General Surgery,Plastic Surgery',
            // 'degree_id' => 'required_if:role,doctor|exists:medical_degrees,id',
            'degree' => 'required_if:role,doctor|in:MBBS,MD,DO,DM,DNB,MS,MCh,BDS,MDS,BAMS,BHMS,BUMS,PhD',
            'license_number' => 'required_if:role,doctor|string|unique:doctors,license_number',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'medical_history' => 'nullable|string',
        ];
    }
}
