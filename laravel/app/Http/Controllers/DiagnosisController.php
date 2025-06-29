<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $query = Diagnosis::with(['patient', 'doctor']);

        if ($request->filled('diagnosis_text')) {
            $query->where('diagnosis_text', 'like', '%'.$request->diagnosis_text.'%');
        }
        if ($request->filled('diagnosis_date')) {
            $query->whereDate('diagnosis_date', $request->diagnosis_date);
        }
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $perPage = $request->input('itemsPerPage', 10);

        return $query->paginate($perPage);
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
