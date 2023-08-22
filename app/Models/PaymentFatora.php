<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentFatora extends Model
{
    use HasFactory;
    protected $table='paymentfatora';
    protected $primaryKey ="id";


    public $timestamps=true;
    protected $fillable = ['is_payment','payment_id','user_id','paymentAmount'];

    public function paymentType()
    {
        return $this->belongsTo(Payment_Type::class, 'payment_id');
    }
    
    public function reservation()
    {
        return $this->belongsTo(ExternalReservation::class, 'paymentfatora_id');
    }

}
