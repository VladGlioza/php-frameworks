<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('properties', PropertyController::class);
Route::apiResource('patients', PatientController::class);
Route::apiResource('doctors', DoctorController::class);
Route::apiResource('diagnoses', DiagnosisController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('treatments', TreatmentController::class);
