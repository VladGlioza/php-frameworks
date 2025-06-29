<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return Patient::all();
    }

    public function store(Request $request)
    {
        $patient = Patient::create($request->all());
        return response()->json($patient, 201);
    }

    public function show(Patient $patient)
    {
        return $patient;
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
        return $patient;
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->noContent();
    }
}
