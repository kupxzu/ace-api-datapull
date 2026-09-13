<?php
namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Disease;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiseaseFactory extends Factory
{
    protected $model = Disease::class;

    public function definition(): array
    {
        return [
            'ConsultationID' => Consultation::factory(),
            'NameDisease'    => fake()->randomElement([
                'Hypertension', 'Type 2 Diabetes', 'Acute Bronchitis', 
                'Migraine without aura', 'Asthma', 'Gastroenteritis', 'Influenza'
            ]),
            'Date'           => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}