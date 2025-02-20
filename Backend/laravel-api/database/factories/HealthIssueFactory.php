<?php


namespace Database\Factories;

use App\Models\HealthIssue;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthIssueFactory extends Factory
{
    protected $model = HealthIssue::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'diagnosis' => $this->faker->sentence(),
            'solution_description' => $this->faker->paragraph(),
            'symptom_description' => $this->faker->paragraph(),
        ];
    }
}
