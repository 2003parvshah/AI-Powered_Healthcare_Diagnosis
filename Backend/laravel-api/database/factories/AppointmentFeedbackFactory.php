<?php
namespace Database\Factories;

use App\Models\AppointmentFeedback;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFeedbackFactory extends Factory
{
    protected $model = AppointmentFeedback::class;

    public function definition()
    {
        return [
            'appointment_id' => Appointment::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'feedback' => $this->faker->paragraph(),
        ];
    }
}
