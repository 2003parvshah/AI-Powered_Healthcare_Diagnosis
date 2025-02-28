<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [];

        // Seed Patients
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "p{$i}",
                'email' => "p{$i}@gmail.com",
                'phone_number' => "12345678{$i}",
                'password' => Hash::make('12345678'),
                'role' => 'patient',
                'otp' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Patient::create([
                'id' => $user->id,
                'date_of_birth' => now()->subYears(25 + $i)->format('Y-m-d'),
                'gender' => $i % 2 == 0 ? 'male' : 'female',
                'medical_history' => 'No significant history',
            ]);
        }

        // Seed Doctors
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "d{$i}",
                'email' => "d{$i}@gmail.com",
                'phone_number' => "1234567{$i}8",
                'password' => Hash::make('12345678'),
                'role' => 'doctor',
                'otp' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Doctor::create([
                'id' => $user->id,
                'specialization' => $i, // Example specialization
                'degree' => $i, // Example degree
                'license_number' => "LIC-1000{$i}",
            ]);
        }
    }
}
