<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Service\ServiceCategorie;
class ServiceCategorieController extends Controller
{
    public function index()
    {
        $servicecategories=ServiceCategorie::all();
        return response()->json($servicecategories);
    }

    
    public function store(Request $request)
        {
            try {
                \Log::info('Incoming request data', $request->all());

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

                $categorie = ServiceCategorie::create([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                return response()->json($categorie, 201);

            } catch (\Exception $e) {
                \Log::error('Service Categories store error: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Server error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }


    
    public function show(string $id)
    {
        $servicecategorie=ServiceCategorie::find($id);
        return response()->json($servicecategorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    try {
        \Log::info('Incoming request data', $request->all());

        $categorie = ServiceCategorie::findOrFail($id);

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

        $categorie->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($categorie, 200);

    } catch (\Exception $e) {
        \Log::error('Service Categories update error: ' . $e->getMessage());
        return response()->json([
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $servicecategorie=ServiceCategorie::find($id);
        $servicecategorie->delete();
        return response()->json($servicecategorie);
    }
}
