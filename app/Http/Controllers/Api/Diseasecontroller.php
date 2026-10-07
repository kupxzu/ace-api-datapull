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
     * Raw disease-surveillance records with demographics.
     *
     * Diagnosis detection and forecasting are handled by Python.
     */
    public function index(Request $request)
    {
        $query = DB::connection('sqlsrv')
            ->table('psPatRegisters as r')

            /*
             * Registry -> Patient master
             */
            ->leftJoin(
                'emdPatients as e',
                'e.PK_emdPatients',
                '=',
                'r.FK_emdPatients'
            )

            /*
             * Patient master -> Patient demographic view
             */
            ->leftJoin(
                'vwPatientMstrList as v',
                'v.patid',
                '=',
                'e.patid'
            )

            ->select([
                'r.PK_psPatRegisters',
                'r.registrydate',

                'r.impression',
                'r.finaldiagnosis',
                'r.dischdiagnosis',

                'v.gender',
                'v.prbarangay',
                'v.prtowncity',
                'v.prprovince',
            ])

            /*
             * Compute the patient's age AT THE TIME
             * of the encounter.
             *
             * Do not use v.Age because that represents
             * the patient's current age.
             */
            ->selectRaw("
                CASE
                    WHEN v.birthdate IS NULL
                         OR r.registrydate IS NULL
                    THEN NULL

                    ELSE
                        DATEDIFF(
                            YEAR,
                            v.birthdate,
                            r.registrydate
                        )
                        -
                        CASE
                            WHEN DATEADD(
                                YEAR,
                                DATEDIFF(
                                    YEAR,
                                    v.birthdate,
                                    r.registrydate
                                ),
                                v.birthdate
                            ) > r.registrydate
                            THEN 1
                            ELSE 0
                        END
                END AS age
            ")

            ->whereNotNull('r.registrydate')

            /*
             * At least one diagnosis-related field
             * must contain something.
             */
            ->where(function ($query) {
                $query

                    ->where(function ($q) {
                        $q->whereNotNull('r.impression')
                            ->whereRaw(
                                "LTRIM(RTRIM(r.impression)) <> ''"
                            );
                    })

                    ->orWhere(function ($q) {
                        $q->whereNotNull('r.finaldiagnosis')
                            ->whereRaw(
                                "LTRIM(RTRIM(r.finaldiagnosis)) <> ''"
                            );
                    })

                    ->orWhere(function ($q) {
                        $q->whereNotNull('r.dischdiagnosis')
                            ->whereRaw(
                                "LTRIM(RTRIM(r.dischdiagnosis)) <> ''"
                            );
                    });
            });

        /*
         * Optional debugging / historical filter:
         *
         * /api/diseases?year=2026
         *
         * Python should normally call /api/diseases
         * without a year so historical training data
         * remains available.
         */
        if ($request->filled('year')) {
            $year = (int) $request->input('year');

            if (
                $year >= 2000 &&
                $year <= now()->year
            ) {
                $query->whereYear(
                    'r.registrydate',
                    $year
                );
            }
        }

        $rows = $query
            ->orderBy('r.registrydate', 'asc')
            ->get();

        $cleanText = function ($value) {
            if ($value === null) {
                return null;
            }

            $value = trim((string) $value);

            if ($value === '') {
                return null;
            }

            if (
                in_array(
                    strtolower($value),
                    [
                        'null',
                        'none',
                        'nan',
                        'n/a',
                        'na',
                        '-',
                    ],
                    true
                )
            ) {
                return null;
            }

            return $value;
        };

        $data = $rows
            ->map(function ($row) use ($cleanText) {

                /*
                 * Validate age.
                 *
                 * Some corrupted/fictitious birthdates
                 * may theoretically create impossible ages.
                 */
                $age = $row->age !== null
                    ? (int) $row->age
                    : null;

                if (
                    $age !== null &&
                    ($age < 0 || $age > 120)
                ) {
                    $age = null;
                }

                return [
                    'registry_id' => $row->PK_psPatRegisters,

                    'registrydate' => $row->registrydate
                        ? date(
                            'Y-m-d H:i:s',
                            strtotime($row->registrydate)
                        )
                        : null,

                    'age' => $age,

                    'gender' => $cleanText(
                        $row->gender
                    ),

                    'barangay' => $cleanText(
                        $row->prbarangay
                    ),

                    'towncity' => $cleanText(
                        $row->prtowncity
                    ),

                    'province' => $cleanText(
                        $row->prprovince
                    ),

                    'impression' => $cleanText(
                        $row->impression
                    ),

                    'finaldiagnosis' => $cleanText(
                        $row->finaldiagnosis
                    ),

                    'dischdiagnosis' => $cleanText(
                        $row->dischdiagnosis
                    ),
                ];
            })

            /*
             * Additional application-side safety.
             */
            ->filter(function ($item) {

                if (!$item['registrydate']) {
                    return false;
                }

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