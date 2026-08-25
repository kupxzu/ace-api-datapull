<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    /**
     * GET /api/diseases
     * Returns disease name + date for every patient.
     */
    public function index(Request $request)
    {
        $diseases = Disease::with(['consultation.patient'])->get();

        $data = $diseases->map(function ($disease) {
            $patient = optional($disease->consultation)->patient;

            return [
                'patient_name' => $patient
                    ? $patient->FirstName . ' ' . $patient->LastName
                    : null,
                'disease'      => $disease->NameDisease,
                'date'         => $disease->Date->format('Y-m-d'),
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $data->count(),
            'data'    => $data,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/diseases
     * Returns disease name + date for ONE specific patient.
     */
    public function byPatient($patientId)
    {
        $diseases = Disease::with('consultation')
            ->whereHas('consultation', function ($q) use ($patientId) {
                $q->where('PatientID', $patientId);
            })
            ->get();

        $data = $diseases->map(function ($disease) {
            return [
                'disease' => $disease->NameDisease,
                'date'    => $disease->Date->format('Y-m-d'),
            ];
        });

        return response()->json([
            'success'    => true,
            'patient_id' => (int) $patientId,
            'count'      => $data->count(),
            'data'       => $data,
        ]);
    }
}