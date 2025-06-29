<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index()
    {
        return Diagnosis::with(['patient', 'doctor'])->get();
    }

    public function store(Request $request)
    {
        $diagnosis = Diagnosis::create($request->all());
        return response()->json($diagnosis, 201);
    }

    public function show(Diagnosis $diagnosis)
    {
        return $diagnosis->load(['patient', 'doctor']);
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        $diagnosis->update($request->all());
        return $diagnosis;
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return response()->noContent();
    }
}
