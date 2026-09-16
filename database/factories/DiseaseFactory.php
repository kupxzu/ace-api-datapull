<?php

namespace Database\Factories;

use App\Models\Disease;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiseaseFactory extends Factory
{
    protected $model = Disease::class;

    public function definition(): array
    {
        return [
            'ConsultationID' => null,

            'NameDisease' => fake()->randomElement([
                // Cardiovascular
                'Hypertension',
                'Coronary Artery Disease',
                'Heart Failure',
                'Arrhythmia',
                'Hyperlipidemia',

                // Diabetes / Endocrine
                'Type 2 Diabetes',
                'Type 1 Diabetes',
                'Hypothyroidism',
                'Hyperthyroidism',
                'Obesity',
                'Metabolic Syndrome',

                // Respiratory
                'Acute Bronchitis',
                'Asthma',
                'Influenza',
                'Pneumonia',
                'Common Cold',
                'Allergic Rhinitis',
                'Sinusitis',
                'Pharyngitis',
                'Tonsillitis',
                'COVID-19',

                // Neurological
                'Migraine without aura',
                'Migraine with aura',
                'Tension Headache',
                'Epilepsy',
                'Vertigo',
                'Peripheral Neuropathy',

                // Gastrointestinal
                'Gastroenteritis',
                'Gastritis',
                'Peptic Ulcer Disease',
                'Gastroesophageal Reflux Disease',
                'Irritable Bowel Syndrome',
                'Constipation',
                'Diarrhea',

                // Infectious
                'Urinary Tract Infection',
                'Dengue Fever',
                'Tuberculosis',
                'Typhoid Fever',
                'Skin Infection',

                // Musculoskeletal
                'Osteoarthritis',
                'Rheumatoid Arthritis',
                'Low Back Pain',
                'Neck Pain',
                'Muscle Strain',
                'Joint Pain',

                // Dermatological
                'Acne',
                'Eczema',
                'Psoriasis',
                'Dermatitis',
                'Fungal Skin Infection',

                // Eye / ENT
                'Conjunctivitis',
                'Cataract',
                'Glaucoma',
                'Otitis Media',
                'Ear Infection',

                // Mental Health
                'Anxiety Disorder',
                'Depressive Disorder',
                'Insomnia',

                // Other
                'Anemia',
                'Chronic Kidney Disease',
                'Kidney Stones',
                'Vitamin D Deficiency',
            ]),

            'Date' => fake()
                ->dateTimeBetween('-2 years', 'now')
                ->format('Y-m-d'),
        ];
    }
}