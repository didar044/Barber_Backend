<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    
    public function index()
    {
        $customers=Customer::paginate(10);
        return response()->json($customers);
    }

    
    public function store(Request $request)
    {
        try{
            \Log::info('incomming all data', $request->all());
             

            $validator=Validator::make($request->all(),[
                'name'          => 'required|string|max:100',
                'email'         => 'required|string|max:100',
                'address'       => 'required|string|max:200',
                'total_visits'  => 'nullable|integer|max:100',
                'last_visit'    => 'nullable|date',
                'notes'         => 'nullable|string|max:200',
                'photo'         => 'nullable',
            ]);
            if($validator->fails()){
                return response()->json([
                 "message"=>"Validation Error",
                 "error"=> $validator->errors(),
                ],422);
            }

            $customer=Customer::create([
                     "name"=>$request->name,
                     "email"=>$request->email,
                     "address"=>$request->address,
                     "phone"=>$request->phone,
                     "total_visits"=>$request->total_visits,
                     "last_visit"=>$request->last_visit,
                     "notes"=>$request->notes,
            ]);

            if($request->hasFile('photo')){
                $imagename = $customer->id . '.' . $request->photo->extension();
                $request->photo->move(public_path('image/customers_img'), $imagename);
                $customer->photo = 'image/customers_img/' . $imagename;
                $customer->save();
            }
        return response()->json($customer);

        }catch(\Exception $e ){
            \Log::error('Customers Store error'.$e->getMessage());
            return response()->json([
                "message"=>"customers sarver Error",
                "error"=> $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $customer=Customer::find($id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          try{
            \Log::info('incomming all data', $request->all());
              
            $customer=Customer::findOrFail($id);
            $validator=Validator::make($request->all(),[
                'name'          => 'required|string|max:100',
                'email'         => 'required|string|max:100',
                'address'       => 'required|string|max:200',
                'total_visits'  => 'nullable|integer|max:100',
                'last_visit'    => 'nullable|date',
                'notes'         => 'nullable|string|max:200',
                'photo'         => 'nullable',
            ]);
            if($validator->fails()){
                return response()->json([
                 "message"=>"Validation Error",
                 "error"=> $validator->errors(),
                ],422);
            }

            $customer->update([
                     "name"=>$request->name,
                     "email"=>$request->email,
                     "address"=>$request->address,
                     "phone"=>$request->phone,
                     "total_visits"=>$request->total_visits,
                     "last_visit"=>$request->last_visit,
                     "notes"=>$request->notes,
            ]);

            if($request->hasFile('photo')){
                $imagename = $customer->id . '.' . $request->photo->extension();
                $request->photo->move(public_path('image/customers_img'), $imagename);
                $customer->photo = 'image/customers_img/' . $imagename;
                $customer->save();
            }
        return response()->json($customer);

        }catch(\Exception $e ){
            \Log::error('Customers Update error'.$e->getMessage());
            return response()->json([
                "message"=>"customers sarver Error",
                "error"=> $e->getMessage(),
            ], 500);
        }
    }

   
    public function destroy(string $id)
    {
        $customer=Customer::find($id);
        if ($customer->photo && file_exists(public_path($customer->photo))) {
                unlink(public_path($customer->photo));
            }
        $customer->delete();
        return response()->json($customer);
    }
}
