<?php

namespace App\Models\Barber;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = "shifts";
     protected $fillable = ['name'];
}
