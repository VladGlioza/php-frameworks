<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Treatment::with('diagnosis');

        if ($request->filled('treatment_plan')) {
            $query->where('treatment_plan', 'like', '%'.$request->treatment_plan.'%');
        }
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', $request->end_date);
        }
        if ($request->filled('diagnosis_id')) {
            $query->where('diagnosis_id', $request->diagnosis_id);
        }

        $perPage = $request->input('itemsPerPage', 10);

        return $query->paginate($perPage);
    }

    public function store(Request $request)
    {
        $treatment = Treatment::create($request->all());
        return response()->json($treatment, 201);
    }

    public function show(Treatment $treatment)
    {
        return $treatment->load('diagnosis');
    }

    public function update(Request $request, Treatment $treatment)
    {
        $treatment->update($request->all());
        return $treatment;
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return response()->noContent();
    }
}
