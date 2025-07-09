<?php

namespace App\Http\Controllers\Api\Barber;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Barber\Barber;

class BarberController extends Controller
{
    
    public function index()
    {
        $barbers = Barber::with('shift')->paginate(10);
        return response()->json($barbers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
        \Log::info('Incoming request data:', $request->all());
        $validator=Validator::make($request->all(),[
                'name' => 'required|string|max:100',
                'father_name' => 'nullable|string|max:100',
                'mother_name' => 'nullable|string|max:100',
                'religion' => 'nullable|string|max:100',
                'gender' => 'nullable|string|max:100',
                'photo' => 'nullable|string',
                'blood_roupe' => 'nullable|string|max:10',
                'address' => 'nullable|string',
                'mobile_number' => 'required|string|max:20',
                'email' => 'nullable|email|max:100',
                'nid_num' => 'nullable|string|max:30',
                'specialization' => 'nullable|string|max:100',
                'exprence_years' => 'nullable|integer|min:0',
                'hire_date' => 'nullable|date',
                'shift_id' => 'required|exists:bar_shifts,id',
                ]);
        
        $barber = Barber::create([
                'name' => $request->name,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                //'photo' => $photoPath,  
                'blood_roupe' => $request->blood_roupe,
                'address' => $request->address,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'nid_num' => $request->nid_num,
                'specialization' => $request->specialization,
                'exprence_years' => $request->exprence_years,
                'hire_date' => $request->hire_date,
                'shift_id' => $request->shift_id,
            ]);


           if ($request->hasFile('photo')) {
                $imagename = $barber->id . '.' . $request->photo->extension();
                $request->photo->move(public_path('image/barber_img'), $imagename);
                $barber->photo = 'image/barber_img/' . $imagename;
                $barber->save();
            }
            return response()->json($barber);

             } catch (\Exception $e) {
        \Log::error('Product store error: '.$e->getMessage());
        return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $barber = Barber::with('shift')->find($id);
           if (!$barber) {
                return response()->json(['message' => 'Barber not found'], 404);
            }
        return response()->json($barber);
    }

   
    public function update(Request $request, string $id)
{     
     try {
        \Log::info('Incoming request data:', $request->all());
    
    $barber = Barber::find($id);

   

    $validator=Validator::make($request->all(),[
                'name' => 'required|string|max:100',
                'father_name' => 'nullable|string|max:100',
                'mother_name' => 'nullable|string|max:100',
                'religion' => 'nullable|string|max:100',
                'gender' => 'nullable|string|max:100',
                'photo' => 'nullable|string',
                'blood_roupe' => 'nullable|string|max:10',
                'address' => 'nullable|string',
                'mobile_number' => 'required|string|max:20',
                'email' => 'nullable|email|max:100',
                'nid_num' => 'nullable|string|max:30',
                'specialization' => 'nullable|string|max:100',
                'exprence_years' => 'nullable|integer|min:0',
                'hire_date' => 'nullable|date',
                'shift_id' => 'required|exists:shifts,id',
                ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $barber->update([
        'name' => $request->name,
        'father_name' => $request->father_name,
        'mother_name' => $request->mother_name,
        'blood_roupe' => $request->blood_roupe,
        'address' => $request->address,
        'mobile_number' => $request->mobile_number,
        'email' => $request->email,
        'religion' => $request->religion,
        'gender' => $request->gender,
        'nid_num' => $request->nid_num,
        'specialization' => $request->specialization,
        'exprence_years' => $request->exprence_years,
        'hire_date' => $request->hire_date,
        'shift_id' => $request->shift_id,
    ]);

    if ($request->hasFile('photo')) {
        if ($barber->photo && file_exists(public_path($barber->photo))) {
            unlink(public_path($barber->photo));
        }

        $imagename = $barber->id . '.' . $request->photo->extension();
        $request->photo->move(public_path('image/barber_img'), $imagename);
        $barber->photo = 'image/barber_img/' . $imagename;
        $barber->save();
    }

    return response()->json($barber);
      } catch (\Exception $e) {
        \Log::error('Product store error: '.$e->getMessage());
        return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
    }
}



    
    public function destroy(string $id)
        {
            $barber = Barber::find($id);

            if ($barber->photo && file_exists(public_path($barber->photo))) {
                unlink(public_path($barber->photo));
            }
            $barber->delete();

            return response()->json([
                'success' => true,
                'message' => 'Barber deleted successfully'
            ]);
        }
}
