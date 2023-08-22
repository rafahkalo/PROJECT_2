<?php

namespace App\Models;

use App\Models\Personal_Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table='reservations';
    
    public $timestamps=true;
     
    protected $fillable=[
        'driver_id',
        'user_id',
        'status',
        'date',
        'travel_time',
        'location',
        'goal',
        'location',
        

        
    ];
    public function boxReservations()
    {
        return $this->hasMany(Box_Reservation::class, 'res_id');
    }

    public function personalReservations()
    {
        return $this->hasMany(Personal_Reservation::class, 'res_id');
    }


    public function complaint_Internal()
    {
        return $this->belongsTo(Complaint_Internal::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }
}
