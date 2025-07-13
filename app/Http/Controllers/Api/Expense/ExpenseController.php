<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense\Expense;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    // List all expenses with pagination
    public function index()
    {
        $expenses = Expense::with('expensecategorie')->latest()->paginate(10);
        return response()->json($expenses);
    }

    // Store a new expense
    public function store(Request $request)
    {
        try {
            \Log::info('Store expenses', $request->all());

            $validator = Validator::make($request->all(), [
                'expense_category_id' => 'required|integer|exists:expense_categories,id',
                'reference_number'    => 'required|string|max:100',
                'expense_for'         => 'required|string|max:355',
                'amount'              => 'required|numeric|min:0',
                'expense_date'        => 'required|date',
                'description'         => 'nullable|string|max:355',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => "Validation Error",
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $expense = Expense::create($request->only([
                'expense_category_id',
                'reference_number',
                'expense_for',
                'amount',
                'expense_date',
                'description',
            ]));

            return response()->json([
                'message' => 'Expense created successfully.',
                'data'    => $expense
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Expense store error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Show single expense
    public function show(string $id)
    {
        $expense = Expense::with('expensecategorie')->find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json($expense);
    }

    // Update expense
    public function update(Request $request, string $id)
    {
        try {
            $expense = Expense::find($id);

            if (!$expense) {
                return response()->json(['message' => 'Expense not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'expense_category_id' => 'required|integer|exists:expense_categories,id',
                'reference_number'    => 'required|string|max:100',
                'expense_for'         => 'required|string|max:355',
                'amount'              => 'required|numeric|min:0',
                'expense_date'        => 'required|date',
                'description'         => 'nullable|string|max:355',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => "Validation Error",
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $expense->update($request->only([
                'expense_category_id',
                'reference_number',
                'expense_for',
                'amount',
                'expense_date',
                'description',
            ]));

            return response()->json([
                'message' => 'Expense updated successfully.',
                'data'    => $expense
            ]);

        } catch (\Exception $e) {
            \Log::error('Expense update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

 
    public function destroy(string $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully.'
        ]);
    }
}
