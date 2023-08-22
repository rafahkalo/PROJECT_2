<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalReservation extends Model
{
    use HasFactory;
    protected $table='external_reservations';
    protected $primaryKey ="id";
    public $timestamps=true;


    protected $fillable=[

        'user_id',
        'travel_id',
        'number_of_persons',
        'cost',
        'paymentfatora_id'
    ];
    public function travel()
    {
        return $this->belongsTo(External_Travel::class, 'travel_id');
    }

    public function paymentFatora()
{
    return $this->belongsTo(PaymentFatora::class, 'paymentfatora_id');
}
}
