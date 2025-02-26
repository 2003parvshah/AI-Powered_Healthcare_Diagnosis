<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorAvailabilitySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'doctor_id' => 6,
                'consultation_hours' => '09:00-17:00',
                'online_consultation_availability' => true,
                'walk_in_availability' => true,
                'appointment_booking_required' => false,
                'time_of_one_appointment' => 30, // 30 minutes
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'doctor_id' => 7,
                'consultation_hours' => '10:00-18:00',
                'online_consultation_availability' => false,
                'walk_in_availability' => true,
                'appointment_booking_required' => true,
                'time_of_one_appointment' => 20, // 20 minutes
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'doctor_id' => 8,
                'consultation_hours' => '08:00-15:00',
                'online_consultation_availability' => true,
                'walk_in_availability' => false,
                'appointment_booking_required' => true,
                'time_of_one_appointment' => 40, // 40 minutes
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'doctor_id' => 9,
                'consultation_hours' => '12:00-20:00',
                'online_consultation_availability' => true,
                'walk_in_availability' => true,
                'appointment_booking_required' => false,
                'time_of_one_appointment' => 25, // 25 minutes
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'doctor_id' => 10,
                'consultation_hours' => '07:00-14:00',
                'online_consultation_availability' => false,
                'walk_in_availability' => false,
                'appointment_booking_required' => true,
                'time_of_one_appointment' => 35, // 35 minutes
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('doctor_availability')->insert($data);
    }
}
