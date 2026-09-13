<?php
namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'PatientID'        => Patient::factory(),
            'DoctorID'         => Doctor::factory(),
            'ConsultationDate' => fake()->dateTimeBetween('-6 months', 'now'),
            'Notes'            => fake()->sentence(10),
        ];
    }
}