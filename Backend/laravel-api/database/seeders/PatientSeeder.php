<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    public function run()
    {
        DB::table('patients')->insert([
            [
                'user_id' => 12,
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'medical_history' => 'No major illnesses',
                'phone_number' => '1234567890',
                'email' => 'p1@gmail.com',
                'address' => '123 Main St, City, Country',
                'past_medical_conditions' => 'None',
                'allergies' => 'Peanuts',
                'blood_pressure' => '120/80',
                'weight' => 75,
                'blood_group' => 'O+',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 13,
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'medical_history' => 'No major illnesses',
                'phone_number' => '1234567890',
                'email' => 'p2@gmail.com',
                'address' => '123 Main St, City, Country',
                'past_medical_conditions' => 'None',
                'allergies' => 'Peanuts',
                'blood_pressure' => '120/80',
                'weight' => 75,
                'blood_group' => 'O+',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ] ,
        ]);
    }
}
