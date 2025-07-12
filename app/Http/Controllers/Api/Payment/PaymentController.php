<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment\Payment;


class PaymentController extends Controller
{
    /**
     * Display a paginated listing of the payments (latest first).
     */
    public function index()
    {
       
        $payments = Payment::with('appointment.customer','appointment.services','appointment.barber')->paginate(10);
        return response()->json($payments);
    }

    /**
     * Store a newly created payment in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'appointment_id' => 'required|exists:appointments,id|unique:payments,appointment_id',
        'reference_number' => 'nullable|string|max:100',
        'total_amount' => 'required|numeric',
        'discount' => 'nullable|numeric',
        'grand_amount' => 'required|numeric',
        'paid_amount' => 'required|numeric',
        'payment_date' => 'required|date',
        'payment_method' => 'required|string|max:50',
    ]);

    try {
        $payment = Payment::create($validated);
        return response()->json($payment, 201);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Payment creation failed',
            'message' => $e->getMessage()
        ], 500);
    }
}


    
    public function show(string $id)
    {
        $payment = Payment::find($id);
        return response()->json($payment);
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'appointment_id' => 'required|exists:bar_appointments,id|unique:bar_payments,appointment_id,' . $id,
            'reference_number' => 'nullable|string|max:100',
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'grand_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

   
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
