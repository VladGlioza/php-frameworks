<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        return Treatment::with('diagnosis')->get();
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
