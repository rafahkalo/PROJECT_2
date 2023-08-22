<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='users';
    public $timestamps=true;
    protected $fillable = [

        'email',
        'password',
        'firstname',
        'lastname',
        'address',
        'age',
        'email',
        'password',
        'phoneOne',
        'phoneTwo',
        'device_key',

    ];
    public function favorite()
    {
        return $this->hasOne(Favorite::class);
    }
    public function wallets()
    {
     return $this->hasMany(Wallet_admin::class,'user_id');

    }

    public function internal_complin()
    {
     return $this->hasMany(Complaint_Internal::class);
 
    }

    public function external_complin()
    {
     return $this->hasMany(Complaint_External::class);
 
    }
    public function evaluation()
    {
     return $this->hasMany(Evaluation::class);
 
    }
    
    public function res()
    {
     return $this->hasMany(Reservation::class);
 
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

       protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}