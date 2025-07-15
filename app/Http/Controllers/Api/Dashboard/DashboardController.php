<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer\Customer;
use App\Models\Appointment\Appointment;
use App\Models\Barber\Barber;
use App\Models\Payment\Payment;
use App\Models\Service\Service;
use App\Models\FeedBack\FeedBack;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   
    public function index()
    {

        $payments=Payment::all();
        $toin=0;
        foreach($payments as $payment){
            $toin=$toin+$payment->paid_amount;
        }

           $topBarbers = Feedback::select(
            'barber_id',
            DB::raw('AVG(rating) as average_rating'),
            DB::raw('COUNT(*) as total_feedbacks')
            )
            ->groupBy('barber_id')
            ->orderByDesc('average_rating')
            ->limit(4)
            ->with('barber:id,name,photo') // make sure you have this relation
            ->get();
        $data=[
             "customers"=>Customer::count(),
             "appos"=>Appointment::where('status','pending')->count(),
             "barbers"=>Barber::count(),
             "services"=>Service::count(),
             "feedback"=>FeedBack::count(),
             "toin"=>$toin, 
             "countfeed"=>$topBarbers,
             "appog"=>Appointment::where('status','confirmed')->count(),
             "appoco"=>Appointment::where('status','completed')->count(),
        ];
        return response()->json($data);
    }


}
