<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->filled('appointment_date')) {
            $query->whereDate('appointment_date', $request->appointment_date);
        }
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('notes')) {
            $query->where('notes', 'like', '%'.$request->notes.'%');
        }

        $perPage = $request->input('itemsPerPage', 10);

        return $query->paginate($perPage);
    }

    public function store(Request $request)
    {
        $appointment = Appointment::create($request->all());
        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        return $appointment->load(['patient', 'doctor']);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->all());
        return $appointment;
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->noContent();
    }
}
