<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiseaseController extends Controller
{
    /**
     * GET /api/diseases
     *
     * Returns raw diagnosis records from psPatRegisters.
     *
     * Important:
     * - registrydate = date/time of the patient registry record
     * - impression, finaldiagnosis, dischdiagnosis are returned separately
     * - disease detection/cleaning will be handled by the Python worker
     * - historical records are NOT filtered by year here because they are
     *   needed for model training and historical surveillance
     */
    public function index(Request $request)
    {
        $query = DB::connection('sqlsrv')
            ->table('psPatRegisters')
            ->select([
                'registrydate',
                'impression',
                'finaldiagnosis',
                'dischdiagnosis',
            ])
            ->whereNotNull('registrydate')
            ->where(function ($query) {

                // Impression has usable content
                $query->where(function ($q) {
                    $q->whereNotNull('impression')
                        ->whereRaw("LTRIM(RTRIM(impression)) <> ''");
                })

                // OR Final Diagnosis has usable content
                ->orWhere(function ($q) {
                    $q->whereNotNull('finaldiagnosis')
                        ->whereRaw("LTRIM(RTRIM(finaldiagnosis)) <> ''");
                })

                // OR Discharge Diagnosis has usable content
                ->orWhere(function ($q) {
                    $q->whereNotNull('dischdiagnosis')
                        ->whereRaw("LTRIM(RTRIM(dischdiagnosis)) <> ''");
                });
            });

        /*
         * Optional year filter.
         *
         * Example:
         * /api/diseases?year=2026
         *
         * IMPORTANT:
         * Python forecasting should normally call /api/diseases
         * WITHOUT the year parameter so historical records remain available.
         */
        if ($request->filled('year')) {
            $year = (int) $request->input('year');

            if ($year >= 2000 && $year <= now()->year) {
                $query->whereYear('registrydate', $year);
            }
        }

        $rows = $query
            ->orderBy('registrydate', 'asc')
            ->get();

        $data = $rows
            ->map(function ($row) {

                $clean = function ($value) {
                    if ($value === null) {
                        return null;
                    }

                    $value = trim((string) $value);

                    if (
                        $value === '' ||
                        in_array(strtolower($value), [
                            'null',
                            'none',
                            'n/a',
                            'na',
                            '-'
                        ], true)
                    ) {
                        return null;
                    }

                    return $value;
                };

                return [
                    'registrydate' => $row->registrydate
                        ? date(
                            'Y-m-d H:i:s',
                            strtotime($row->registrydate)
                        )
                        : null,

                    'impression' => $clean($row->impression),

                    'finaldiagnosis' => $clean(
                        $row->finaldiagnosis
                    ),

                    'dischdiagnosis' => $clean(
                        $row->dischdiagnosis
                    ),
                ];
            })
            ->filter(function ($item) {

                // Valid registry date is required
                if (empty($item['registrydate'])) {
                    return false;
                }

                // At least one diagnosis-related field must exist
                return
                    $item['impression'] !== null ||
                    $item['finaldiagnosis'] !== null ||
                    $item['dischdiagnosis'] !== null;
            })
            ->values();

        return response()->json([
            'success' => true,
            'count' => $data->count(),
            'data' => $data,
        ]);
    }
}