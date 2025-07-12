<?php

namespace App\Models\Appointment;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barber\Barber;
use App\Models\Appointment\Appointment_Service;
use App\Models\Customer\Customer;
use App\Models\Barber\Shift;

class Appointment extends Model
{
    protected $table="appointments";
public function services() {
    return $this->hasMany(Appointment_Service::class,'appointment_id');
}




public function barber() {
    return $this->belongsTo(Barber::class);
}

public function customer() {
    return $this->belongsTo(Customer::class,'customer_id');
}

public function shift() {
    return $this->belongsTo(Shift::class);
}
protected $fillable = [
    'barber_id',
    'customer_id',
    'shift_id',
    'appointment_date',
    'appointment_time',
    'status',
    'total_amount',
    'notes',
];



}
