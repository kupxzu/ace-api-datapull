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