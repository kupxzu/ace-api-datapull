<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create base entities
        $doctors = Doctor::factory()->count(30)->create();
        $patients = Patient::factory()->count(2000)->create();

        // 2. Create 300 Consultations linked to random Doctors and Patients
        $consultations = Consultation::factory()
            ->count(2000)
            ->make()
            ->each(function ($consultation) use ($doctors, $patients) {
                $consultation->DoctorID = $doctors->random()->DoctorID;
                $consultation->PatientID = $patients->random()->PatientID;
                $consultation->save();
            });

        // 3. Create 300 Disease records linked to existing Consultations
        Disease::factory()
            ->count(2000)
            ->make()
            ->each(function ($disease) use ($consultations) {
                $disease->ConsultationID = $consultations->random()->ConsultationID;
                $disease->save();
            });
    }
}