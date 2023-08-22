<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    
    protected $table='cars';
    public $timestamps=true;
    protected $fillable = ['color_id','numberOfSeating','type_id','number','image','cost','driver_id','office_id','agency_image'];

  
    
    public function driver(){
        return $this->belongsTo('App\Models\Driver','driver_id');
      }
      
    public function office()
    {
        return $this->belongsTo(Office::class,'office_id');
    }

}