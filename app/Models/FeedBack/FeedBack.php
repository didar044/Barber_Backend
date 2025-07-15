<?php

namespace App\Models\FeedBack;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;
use App\Models\Barber\Barber;
use App\Models\Appointment\Appointment;

class FeedBack extends Model
{
    protected $table="feedbacks";
    protected $fillable=['barber_id','customer_id','appointment_id','rating','message','submitted_at'];
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function barber(){
        return $this->belongsTo(Barber::class,'barber_id');
    }
    public function appointment(){
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
}
