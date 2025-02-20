<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'p1',
                'email' => 'p1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'remember_token' => null,
                'role' => 'user',
                'profile_photo_path' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'p2',
                'email' => 'p2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'remember_token' => null,
                'role' => 'user',
                'profile_photo_path' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'd1',
                'email' => 'd1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'remember_token' => null,
                'role' => 'doctor',
                'profile_photo_path' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ] ,
            [
                'name' => 'd2',
                'email' => 'd2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'remember_token' => null,
                'role' => 'doctor',
                'profile_photo_path' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ] ,
        ]);
    }
}
