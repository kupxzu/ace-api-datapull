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
            'FirstName' => fake()->firstName(),
            'LastName' => fake()->lastName(),

            'Specialization' => fake()->randomElement([
                'General Practice',
                'Family Medicine',
                'Internal Medicine',
                'Cardiology',
                'Neurology',
                'Neurosurgery',
                'Pediatrics',
                'Neonatology',
                'Obstetrics and Gynecology',
                'Orthopedics',
                'Rheumatology',
                'Dermatology',
                'Plastic Surgery',
                'Ophthalmology',
                'Otolaryngology',
                'Pulmonology',
                'Gastroenterology',
                'Nephrology',
                'Urology',
                'Endocrinology',
                'Diabetology',
                'Infectious Disease',
                'Hematology',
                'Oncology',
                'Psychiatry',
                'Emergency Medicine',
                'Anesthesiology',
                'Radiology',
                'Pathology',
                'Geriatrics',
                'Sports Medicine',
                'Pain Medicine',
                'Family Medicine',
                'Critical Care Medicine',
                'Nuclear Medicine',
                'Occupational Medicine',
                'Rehabilitation Medicine',
                'Vascular Surgery',
                'General Surgery',
                'Colorectal Surgery',
                'Thoracic Surgery',
            ]),

            'ContactNumber' => fake()->numerify('09#########'),

            'Email' => fake()->unique()->safeEmail(),

            'CreatedAt' => fake()->dateTimeBetween(
                '-2 years',
                'now'
            ),
        ];
    }
}