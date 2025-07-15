<?php

namespace App\Http\Controllers\Api\FeedBack;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedBack\FeedBack;
use Illuminate\Support\Facades\Validator;

class FeedBackController extends Controller
{
    
    public function index()
    {
        $feedbacks=FeedBack::with('customer','barber','appointment')->latest()->paginate(10);
        return response()->json($feedbacks);
    }

   
    public function store(Request $request)
    {
        try{
            \Log::info('Store All Feedbacks', $request->all());
            $validatior=Validator::make($request->all(),[
                    'barber_id' => 'required|exists:barbers,id',
                    'customer_id' => 'required|exists:customers,id',
                    'appointment_id' => 'nullable|exists:appointments,id',
                    'rating' => 'required|integer|between:1,5',
                    'message' => 'nullable|string|max:1000',
            ]);
            if($validatior->fails()){
                return response()->json([
                    'message'=>'Validation Error',
                    'error'=>$validatior->errors(),
                ],422);
            }

            $feedback=FeedBack::create($request->only([
                 'barber_id','customer_id','appointment_id','rating','message','submitted_at',
            ]));

            return response()->json([
                'message'=>"Feedback Submit Successfull ",
                'data'=>$feedback,
            ],201);

        }catch(\Exception $e ){
                \Log::error('Feedback Store Error' .$e->getMessage());
                return response()->json([
                    "message"=>"Feedback Store Error",
                    "error"=> $e->getMessage(),
                ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
