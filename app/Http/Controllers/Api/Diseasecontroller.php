<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    /**
     * GET /api/diseases
     * Returns disease name + date for every patient.
     */
    public function index(Request $request)
    {
        // Kunin ang records mula sa MSSQL table kung saan may laman ang impression, finaldiagnosis, o dischdiagnosis
        $rows = DB::connection('sqlsrv')
            ->table('psPatRegisters')
            ->select('registrydate', 'impression', 'finaldiagnosis', 'dischdiagnosis')
            ->where(function ($query) {
                $query->whereNotNull('impression')->where('impression', '<>', '')
                    ->orWhereNotNull('finaldiagnosis')->where('finaldiagnosis', '<>', '')
                    ->orWhereNotNull('dischdiagnosis')->where('dischdiagnosis', '<>', '');
            })
            ->orderBy('registrydate', 'desc')
            ->get();

        $data = $rows->map(function ($row) {
            // Pagsama-samahin o piliin kung saan nakita ang diagnosis
            $diagnosisText = trim($row->finaldiagnosis ?: ($row->dischdiagnosis ?: $row->impression));

            return [
                'patient_name' => null, // O kunin kung meron man sa table
                'disease'      => $diagnosisText,
                'date'         => $row->registrydate ? date('Y-m-d', strtotime($row->registrydate)) : null,
            ];
        })->filter(function ($item) {
            return !empty($item['disease']) && !empty($item['date']);
        });

        return response()->json([
            'success' => true,
            'count'   => $data->count(),
            'data'    => $data->values(),
        ]);
    }
}