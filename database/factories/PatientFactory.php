<?php
namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'FirstName'     => fake()->firstName(),
            'LastName'      => fake()->lastName(),
            'Gender'        => fake()->randomElement(['Male', 'Female']),
            'BirthDate'     => fake()->dateTimeBetween('-70 years', '-1 years')->format('Y-m-d'),
            'ContactNumber' => fake()->phoneNumber(),
            'Address'       => fake()->address(),
            'CreatedAt'     => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}