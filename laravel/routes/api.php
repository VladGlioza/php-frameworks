<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('diagnoses', DiagnosisController::class);
    Route::apiResource('treatments', TreatmentController::class);
});
Route::post('/login', [AuthController::class, 'login']);
