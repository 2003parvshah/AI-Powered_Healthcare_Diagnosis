<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorTimeTableSeeder extends Seeder
{
    public function run()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $doctorIds = [6, 7];

        $data = [];

        foreach ($doctorIds as $doctorId) {
            foreach ($days as $day) {
                $data[] = [
                    'doctor_id' => $doctorId,
                    'day' => $day,
                    'start_time' => '9:00:00',  // Example start time
                    'end_time' => '12:00:00',    // Example end time
                    'address' => 'Clinic Address ' . $doctorId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        foreach ($doctorIds as $doctorId) {
            foreach ($days as $day) {
                $data[] = [
                    'doctor_id' => $doctorId,
                    'day' => $day,
                    'start_time' => '16:00:00',  // Example start time
                    'end_time' => '21:00:00',    // Example end time
                    'address' => 'Clinic Address ' . $doctorId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        DB::table('doctor_timeTable')->insert($data);
    }
}
