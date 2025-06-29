<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->filled('specialty')) {
            $query->where('specialty', 'like', '%'.$request->specialty.'%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%'.$request->phone.'%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%'.$request->email.'%');
        }

        $perPage = $request->input('itemsPerPage', 10);

        return $query->paginate($perPage);
    }

    public function store(Request $request)
    {
        $doctor = Doctor::create($request->all());
        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor)
    {
        return $doctor;
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return $doctor;
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->noContent();
    }
}
