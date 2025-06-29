<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('date_of_birth')) {
            $query->whereDate('date_of_birth', $request->date_of_birth);
        }
        if ($request->filled('contact_info')) {
            $query->where('contact_info', 'like', '%'.$request->contact_info.'%');
        }

        $perPage = $request->input('itemsPerPage', 10);

        return $query->paginate($perPage);
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
