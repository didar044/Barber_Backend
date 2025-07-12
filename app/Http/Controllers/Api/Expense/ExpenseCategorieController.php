<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense\ExpenseCategorie;
use Illuminate\Support\Facades\Validator;

class ExpenseCategorieController extends Controller
{
    public function index()
    {
        $expenses = ExpenseCategorie::latest('id')->get();
        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Get all data', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $expensecategorie = ExpenseCategorie::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Expense category created successfully',
                'data' => $expensecategorie
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Categorie Store error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $expensecategorie = ExpenseCategorie::find($id);

        if (!$expensecategorie) {
            return response()->json(['message' => 'Expense category not found'], 404);
        }

        return response()->json($expensecategorie);
    }

    public function update(Request $request, string $id)
    {
        try {
            $expensecategorie = ExpenseCategorie::find($id);

            if (!$expensecategorie) {
                return response()->json(['message' => 'Expense category not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $expensecategorie->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Expense category updated successfully',
                'data' => $expensecategorie
            ]);

        } catch (\Exception $e) {
            \Log::error('Categorie Update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $expensecategorie = ExpenseCategorie::find($id);

            if (!$expensecategorie) {
                return response()->json(['message' => 'Expense category not found'], 404);
            }

            $expensecategorie->delete();

            return response()->json(['message' => 'Expense category deleted successfully']);

        } catch (\Exception $e) {
            \Log::error('Categorie Delete error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
