<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Health_issueSeeder extends Seeder
{
    public function run()
    {
        DB::table('health_issues')->insert([
            [
                'patient_id' => 1,
                'symptoms' => 'Fever, headache, and fatigue',
                'report_pdf' => 'reports/report_1.pdf',
                'report_image' => 'reports/report_1.jpg',
                'doctor_type' => 'General Physician',
                'diagnosis' => 'Viral Fever',
                'solution' => 'Rest, hydration, and paracetamol for fever.',
                'other_info' => 'Follow up in 3 days if symptoms persist.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 1,
                'symptoms' => 'Chest pain and shortness of breath',
                'report_pdf' => 'reports/report_2.pdf',
                'report_image' => 'reports/report_2.jpg',
                'doctor_type' => 'Cardiologist',
                'diagnosis' => 'Mild Angina',
                'solution' => 'Prescribed aspirin and lifestyle modifications.',
                'other_info' => 'Recommended ECG and further cardiac tests.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 2,
                'symptoms' => 'Severe lower back pain',
                'report_pdf' => 'reports/report_3.pdf',
                'report_image' => 'reports/report_3.jpg',
                'doctor_type' => 'Orthopedic',
                'diagnosis' => 'Lumbar Strain',
                'solution' => 'Physical therapy and pain relief medication.',
                'other_info' => 'Avoid heavy lifting for two weeks.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
