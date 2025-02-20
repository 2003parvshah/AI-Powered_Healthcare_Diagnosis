<?php
namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialization;
use App\Models\MedicalDegree;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'specialization_id' => Specialization::factory(),
            'degree_id' => MedicalDegree::factory(),
            'license_number' => $this->faker->unique()->numerify('LIC####'),
        ];
    }
}
