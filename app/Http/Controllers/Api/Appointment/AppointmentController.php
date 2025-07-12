<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\Appointment_Service;
use App\Models\Service\Service;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    // GET: /api/appointments
    public function index()
    {
        $appointments = Appointment::with(['barber', 'customer', 'shift', 'services'])->latest()->paginate(10);
        return response()->json($appointments);
    }

  
    public function show($id)
{
    try {
        $appointment = Appointment::with(['barber', 'customer', 'shift', 'services'])->findOrFail($id);
        return response()->json($appointment);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Appointment not found or server error',
            'message' => $e->getMessage()
        ], 500);
    }
}




public function store(Request $request)
{
    $request->validate([
        'barber_id' => 'required|exists:barbers,id',
        'customer_id' => 'required|exists:customers,id',
        'shift_id' => 'required|exists:shifts,id',
        'appointment_date' => 'required|date',
        'appointment_time' => 'required|date_format:H:i',
        'service_ids' => 'required|array|min:1',
        'service_ids.*' => 'exists:services,id',
        'notes' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $services = Service::whereIn('id', $request->service_ids)->get();

        $appointment = Appointment::create([
            'barber_id' => $request->barber_id,
            'customer_id' => $request->customer_id,
            'shift_id' => $request->shift_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'pending',
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
        ]);

        foreach ($services as $service) {
            Appointment_Service::create([
                'appointment_id' => $appointment->id,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Appointment created successfully',
            'data' => $appointment->load('services'),
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Appointment creation failed: ' . $e->getMessage());

        return response()->json([
            'message' => 'Error creating appointment',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    // PUT: /api/appointments/{id}
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'customer_id' => 'required|exists:customers,id',
            'shift_id' => 'required|exists:shifts,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $services = Service::whereIn('id', $request->service_ids)->get();
            $total = $services->sum('price');

            $appointment->update([
                'barber_id' => $request->barber_id,
                'customer_id' => $request->customer_id,
                'shift_id' => $request->shift_id,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'status' => $request->status ?? $appointment->status,
                'total_amount' => $total,
                'notes' => $request->notes,
            ]);

            // Remove old services
            Appointment_Service::where('appointment_id', $appointment->id)->delete();

            // Add updated services
            foreach ($services as $service) {
                Appointment_Service::create([
                    'appointment_id' => $appointment->id,
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'service_price' => $service->price,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Appointment updated successfully',
                'data' => $appointment->load('services'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error updating appointment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // DELETE: /api/appointments/{id}
  public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        // Delete related services first
        Appointment_Service::where('appointment_id', $id)->delete();

        // Then delete the appointment itself
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully']);
    }








    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->status = $request->status;
        $appointment->save();

        return response()->json([
            'message' => 'Appointment status updated successfully',
            'data' => $appointment,
        ]);
    }

}