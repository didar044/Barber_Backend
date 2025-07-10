<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class ServiceCategorie extends Model
{
    protected $table="service_categories";
    protected $fillable=[
         'id',
         'name',
         'description',

    ];
}
