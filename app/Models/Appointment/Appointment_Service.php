<?php

namespace App\Models\Appointment;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Service;
use App\Models\Appointment\Appointment;

class Appointment_Service extends Model
{
   protected $table="appointment_service";
   public function service() {
    return $this->belongsTo(Service::class);
}

public function appointment() {
    return $this->belongsTo(Appointment::class);
}
// Appointment_Service.php
protected $fillable = [
    'appointment_id',
    'service_id',
    'service_name',
    'service_price',
];
}
