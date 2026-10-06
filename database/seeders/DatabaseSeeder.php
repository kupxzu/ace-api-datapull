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
        /*
        |--------------------------------------------------------------------------
        | Doctors
        |--------------------------------------------------------------------------
        */

        $doctors = Doctor::factory()
            ->count(5000)
            ->create();

        /*
        |--------------------------------------------------------------------------
        | Patients
        |--------------------------------------------------------------------------
        */

        $patients = Patient::factory()
            ->count(1400)
            ->create();

        /*
        |--------------------------------------------------------------------------
        | Consultations
        |--------------------------------------------------------------------------
        */

        $consultations = Consultation::factory()
            ->count(5000)
            ->make()
            ->each(function ($consultation) use ($doctors, $patients) {

                $consultation->DoctorID =
                    $doctors->random()->DoctorID;

                $consultation->PatientID =
                    $patients->random()->PatientID;

                $consultation->save();
            });

        /*
        |--------------------------------------------------------------------------
        | Diseases
        |--------------------------------------------------------------------------
        */

        Disease::factory()
            ->count(5000)
            ->make()
            ->each(function ($disease) use ($consultations) {

                $disease->ConsultationID =
                    $consultations->random()->ConsultationID;

                $disease->save();
            });
    }
}