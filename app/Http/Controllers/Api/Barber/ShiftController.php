<?php

namespace App\Http\Controllers\Api\Barber;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barber\Shift;

class ShiftController extends Controller
{
    
    public function index()
    {
        $shifts=Shift::all();
        return response()->json($shifts);   
    }

    
    public function store(Request $request)
    {
        $shifts=new Shift();
        $shifts->name=$request->name;
        $shifts->start_time=$request->start_time;
        $shifts->end_time=$request->end_time;
        $shifts->name=$request->name;
        $shifts->save();
        return response()->json($shifts,201);
    }

   
    public function show(string $id)
    {
         $shift=Shift::find($id);
         return response()->json($shift,201);
    }
    
    
    public function update(Request $request, string $id)
    {
         $shift=Shift::findOrFail($id);
         $shift->name=$request->name;
        $shift->start_time=$request->start_time;
        $shift->end_time=$request->end_time;
         $shift->save();
         return response()->json($shift,200);
    }

    
    public function destroy(string $id)
    {
        $shift=Shift::find($id);
        $shift->delete();
        return response()->json($shift);
    }
}
