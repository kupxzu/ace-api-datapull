<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DiseaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Idagdag ang mga ito sa routes/api.php ng Laravel project mo
| (o i-merge kung may existing ka nang laman ang file na ito).
*/

// Disease records (FinalDiagnosis + date) pulled from psPatRegisters (MSSQL, sqlsrv connection)
Route::get('/diseases', [DiseaseController::class, 'index']);

Route::get('/debug-php', function () {
    return response()->json([
        'version' => phpversion(),
        'ini_file' => php_ini_loaded_file(),
        'sqlsrv_loaded' => extension_loaded('sqlsrv'),
        'pdo_sqlsrv_loaded' => extension_loaded('pdo_sqlsrv'),
    ]);
});