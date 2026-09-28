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
                // Infectious & Outbreak-prone (Maganda para sa LSTM forecasting)
                'Dengue Fever', 
                'Acute Gastroenteritis', 
                'Influenza', 
                'Pneumonia', 
                'Leptospirosis', 
                'Typhoid Fever',
                'COVID-19',
                'Acute Upper Respiratory Tract Infection (AURTI)',
                
                // Common Chronic & Lifestyle Diseases
                'Hypertension', 
                'Type 2 Diabetes Mellitus', 
                'Bronchial Asthma', 
                'Chronic Obstructive Pulmonary Disease (COPD)',
                
                // Other Common Clinic/Hospital Cases
                'Urinary Tract Infection (UTI)', 
                'Acute Bronchitis', 
                'Migraine without aura', 
                'Gastroenteritis',
                'Skin Infection / Cellulitis',
                'Acute Appendicitis'
            ]),
            'Date'           => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}