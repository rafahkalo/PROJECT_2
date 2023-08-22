<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet_admin extends Model
{
    use HasFactory;
    protected $table='wallet_admin';
    public $timestamps=true;

    protected $fillable = ['amount','code','user_id'];

    public function user()
    {
     return $this->belongsTo(User::class,'user_id');
 
    }
}
