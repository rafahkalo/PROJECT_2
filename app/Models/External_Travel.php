<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class External_Travel extends Model
{
    use HasFactory;
    public $timestamps=true;
    protected $table='external_travel';
  
    protected $fillable=['office_id','driver_id','last_time_paid','status','cost','destnation',
    'time','location','date'];

    
    public function office()
    {
        return $this->belongsTo(Office::class,'office_id');
    }
    
    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }
    public function seats()
    {
        return $this->hasMany(Seat::class,'travel_id');
    }
    public function externalReservations()
    {
        return $this->hasMany(ExternalReservation::class, 'travel_id');
    }
}
