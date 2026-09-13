<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Diseasecontroller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Idagdag ang mga ito sa routes/api.php ng Laravel project mo
| (o i-merge kung may existing ka nang laman ang file na ito).
*/

// Lahat ng disease + date records (kasama patient name)
Route::get('/diseases', [Diseasecontroller ::class, 'index']);

// Disease + date ng ISANG specific patient lang
Route::get('/patients/{patientId}/diseases', [Diseasecontroller ::class, 'byPatient']);


Route::get('/debug-php', function () {
    return response()->json([
        'version' => phpversion(),
        'ini_file' => php_ini_loaded_file(),
        'sqlsrv_loaded' => extension_loaded('sqlsrv'),
        'pdo_sqlsrv_loaded' => extension_loaded('pdo_sqlsrv'),
    ]);
});