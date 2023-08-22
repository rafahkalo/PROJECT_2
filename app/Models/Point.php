<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $table='points';
    public $timestamps=true;
    protected $fillable = ['point','travel_type_id','driver_id'];



    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function travel_type()
    {
        return $this->belongsTo(Type_travel::class);
    }
    public function bonase()
    {
     return $this->hasMany(Bonase::class);
 
    }
}
