<?php
namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'medical_history' => $this->faker->text(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'address' => $this->faker->address(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'past_medical_conditions' => $this->faker->sentence(),
            'allergies' => $this->faker->sentence(),
            'blood_pressure' => $this->faker->randomElement(['Normal', 'High', 'Low']),
            'weight' => $this->faker->randomFloat(2, 40, 100),
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
        ];
    }
}
