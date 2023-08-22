<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $table='seating';
    protected $primaryKey ="id";
    public $timestamps=true;


    protected $fillable=[

        'numberOfSeat',
        'travel_id',
        'user_id'
    ];
    public function travel()
    {
        return $this->belongsTo(External_Travel::class,'travel_id');
    }
}
