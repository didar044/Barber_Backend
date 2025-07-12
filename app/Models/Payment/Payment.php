<?php

namespace App\Models\Payment;

use App\Models\Appointment\Appointment;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";

    protected $fillable = [
        'appointment_id',
        'reference_number',
        'total_amount',
        'discount',
        'grand_amount',
        'paid_amount',
        'payment_date',
        'payment_method',
    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
