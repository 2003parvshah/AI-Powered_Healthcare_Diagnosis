<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\MedicalDegree;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\AppointmentFeedback;
use App\Models\HealthIssue;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Specializations and Degrees first (they are required for doctors)
        // $specializations = Specialization::factory(5)->create();
        // $degrees = MedicalDegree::factory(5)->create();

        // Create Users
        // User::factory(20)->create();

        // Create Doctors (each doctor will need a specialization and degree)
        // Doctor::factory(10)->create([
        // 'specialization_id' => $specializations->random()->id,
        // 'degree_id' => $degrees->random()->id,
        // ]);

        // Create Patients
        // Patient::factory(20)->create();

        // Create Appointments
        // Appointment::factory(30)->create();

        // Create Feedback for Appointments
        // AppointmentFeedback::factory(30)->create();

        // Create Health Issues
        // HealthIssue::factory(30)->create();
    }
}
