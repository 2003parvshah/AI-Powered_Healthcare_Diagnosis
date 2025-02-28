<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        // Sample appointment data
        $appointments = [
            [
                'doctor_id' => 6,
                // 'patient_id' => 1,
                'health_issues_id' => 1,
                // 'appointment_date' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'), // 2 days later
                'appointment_date' => "2025-03-02 14:00:00",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'doctor_id' => 7,
                // 'patient_id' => 1,
                'health_issues_id' => 2,
                // 'appointment_date' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'), // 5 days later
                'appointment_date' => "2025-03-01 14:00:00",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'doctor_id' => 6,
                // 'patient_id' => 2,
                'health_issues_id' => 3,
                'appointment_date' => "2025-03-01 14:00:00",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into the appointments table
        DB::table('appointments')->insert($appointments);
    }
}
