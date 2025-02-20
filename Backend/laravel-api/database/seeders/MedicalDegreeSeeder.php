<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalDegreeSeeder extends Seeder
{
    public function run()
    {
        $medical_degrees = [
            'MBBS', 'MD', 'DO', 'DM', 'DNB',
            'MS', 'MCh', 'BDS', 'MDS', 'BAMS',
            'BHMS', 'BUMS', 'PhD'
        ];

        foreach ($medical_degrees as $degree) {
            DB::table('medical_degrees')->insert([
                'name' => $degree,
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ]);
        }
    }
}
