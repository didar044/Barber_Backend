<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table="customers";
    protected $fillable=['id','name','phone','email','address','photo','total_visits','last_visit','notes'];
}
