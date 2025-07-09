<?php

namespace App\Models\Barber;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barber\Shift;

class Barber extends Model
{
    protected $table = "barbers";

    protected $fillable = [
        'name',
        'gender',
        'religion',
        'father_name',
        'mother_name',
        'photo',
        'blood_roupe',
        'address',
        'mobile_number',
        'email',
        'nid_num',
        'specialization',
        'exprence_years',
        'hire_date',
        'shift_id',
    ];

    // Optional: Relationship to Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
