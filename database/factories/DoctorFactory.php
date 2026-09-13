<?php
namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'FirstName'     => fake()->firstName(),
            'LastName'      => fake()->lastName(),
            'Specialization' => fake()->randomElement(['Cardiology', 'Neurology', 'Pediatrics', 'General Practice', 'Orthopedics', 'Dermatology']),
            'ContactNumber'  => fake()->phoneNumber(),
            'Email'          => fake()->safeEmail(),
            'CreatedAt'      => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}