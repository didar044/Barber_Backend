<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Service\Service;

class ServiceController extends Controller
{
    
    public function index()
    {
        $services=Service::with('category')->paginate(10);
        return response()->json($services, 200);
    }

    
    public function store(Request $request)
    {
        try{
            \Log::info('Incoming Request Data',$request->all());
            $validator=Validator::make($request->all(),[
                 'name' => 'required|string|max:100',
                'service_category_id' => 'required|exists:service_categories,id',
                'price' => 'required|numeric|min:0',
                'duration_minutes' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'status' => 'nullable|in:active,inactive',
            ]);
            
            if ($validator->fails()){
                return response()->json([
                    'message'=>'Validation Faild',
                    'errors'=>$validator->errors()
                ],422);
            }
            $service=Service::create([
              'name'=>$request->name,
              'service_category_id'=>$request->service_category_id,
              'price'=>$request->price,
              'duration_minutes'=>$request->duration_minutes,
              'description'=>$request->description,
              'status'=>$request->status,
            ]);

          return response()->json($service,201);

        }catch(\Exception $e){
            \Log::error('Service Store Error'. $e->getMessage());
            return response()->json([
                'message'=>'server Error',
                'error'=> $e->getMessage()
            ],500);
        }
    }

   
    public function show(string $id)
    {
        $service=Service::find($id);
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            \Log::info('Incoming Request Data', $request->all());

             $service=Service::findOrFail($id); 
             $validator=Validator::make($request->all(),[
                 'name' => 'required|string|max:100',
                'service_category_id' => 'required|exists:service_categories,id',
                'price' => 'required|numeric|min:0',
                'duration_minutes' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'status' => 'nullable|in:active,inactive',
            ]);

            
            if ($validator->fails()){
                return response()->json([
                    'message'=>'Validation Faild',
                    'errors'=>$validator->errors()
                ],422);
            }
            $service->update([
              'name'=>$request->name,
              'service_category_id'=>$request->service_category_id,
              'price'=>$request->price,
              'duration_minutes'=>$request->duration_minutes,
              'description'=>$request->description,
              'status'=>$request->status,
            ]);
            return response()->json($service, 200);


        }catch(\Exception $e){
            \Log::error('Service Update Error'. $e->getMessage());
            return response()->json([
                'message'=>'server error',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service=Service::find($id);
        $service->delete();
        return response()->json($service);
    }
}
