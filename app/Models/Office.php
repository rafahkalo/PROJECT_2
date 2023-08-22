<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\Office as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Office extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table='offices';
    
    public $timestamps=true;
  
    protected $fillable=[
        'email',
        'password',
        'name',
        'status',
        'branch_id',
        'type_id',
        'star_id',
        'location',
        'image',
        'phoneOne',
        'phoneTwo',
        'contract',
        'discreption',
        'device_key',
        
        
    ];

    public function number()
    {
     return $this->hasMany(Number::class);
 
    }
    
    public function wallets()
    {
     return $this->hasMany(Wallet_Office::class);
 
    }
    public function stars()
    {
        return $this->belongsTo(Star::class,'star_id');
    }
    
    
    public function type_travel()
    {
        return $this->belongsTo(Type_travel::class, 'type_id');
    }
    public function bonase()
    {
        return $this->belongsTo(Bonase::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    
    
    public function car()
    {
     return $this->hasMany(Car::class);
 
    }
      
    public function driver()
    {
     return $this->hasMany(Driver::class);
 
    }
    
    public function res()
    {
     return $this->hasMany(Reservation::class);
 
    }
  
    public function external_travels()
    {
     return $this->hasMany(External_Travel::class,'office_id');
 
    }
  
    public function offir()
    {
     return $this->hasMany(Offire::class);
 
    }
    
    public function rewards()
    {
        return $this->belongsTo(Reward::class);
    }
}