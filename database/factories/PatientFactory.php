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
            'FirstName' => fake()->firstName(),

            'LastName' => fake()->lastName(),

            'Gender' => fake()->randomElement([
                'Male',
                'Female',
            ]),

            'BirthDate' => fake()->dateTimeBetween(
                '-80 years',
                '-1 year'
            )->format('Y-m-d'),

            'ContactNumber' => fake()->numerify('09#########'),

            'Address' => fake()->randomElement([
                'Tuguegarao City, Cagayan',
                'Iguig, Cagayan',
                'Peñablanca, Cagayan',
                'Solana, Cagayan',
                'Aparri, Cagayan',
                'Lal-lo, Cagayan',
                'Gonzaga, Cagayan',
                'Amulung, Cagayan',
                'Alcala, Cagayan',
                'Baggao, Cagayan',
                'Gattaran, Cagayan',
                'Lasam, Cagayan',
                'Sanchez-Mira, Cagayan',
                'Camalaniugan, Cagayan',
                'Abulug, Cagayan',
            ]),

            'CreatedAt' => fake()->dateTimeBetween(
                '-2 years',
                'now'
            ),
        ];
    }
}