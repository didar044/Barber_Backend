<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\ServiceCategorie;

class Service extends Model
{
    protected $table="services";
    protected $fillable=[
        'id','name','service_category_id','price','duration_minutes','description','status',
    ];
    public function category(){
           return $this->belongsTo(ServiceCategorie::class,'service_category_id');
    }
}
