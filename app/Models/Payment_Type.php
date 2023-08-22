<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_Type extends Model
{
    use HasFactory;
    protected $table='payment_types';
    protected $primaryKey ="id";
    public $timestamps=true;


    protected $fillable=[

        'payment_type',
        
    ];
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->payment_type = in_array($model->payment_type, ['electronic', 'cash']) ? $model->payment_type : null;
        });
    }
    public function paymentFatoras()
    {
        return $this->hasMany(PaymentFatora::class, 'payment_id');
    }
   

}
