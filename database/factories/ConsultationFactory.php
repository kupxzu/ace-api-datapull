<?php

namespace Database\Factories;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'PatientID' => null,

            'DoctorID' => null,

            'ConsultationDate' => fake()
                ->dateTimeBetween('-2 years', 'now'),

            'Notes' => fake()->randomElement([
                'Patient presented with mild symptoms.',
                'Patient reported recurring symptoms.',
                'Routine medical consultation.',
                'Follow-up consultation.',
                'Patient advised to monitor symptoms.',
                'Patient advised to increase fluid intake.',
                'Patient advised to rest and observe symptoms.',
                'Medication prescribed as indicated.',
                'Laboratory examination recommended.',
                'Patient condition stable.',
                'Further evaluation recommended.',
                'Patient reported improvement from previous visit.',
                'Patient reported persistent symptoms.',
                'Vital signs monitored during consultation.',
                'Follow-up visit scheduled.',
            ]),
        ];
    }
}