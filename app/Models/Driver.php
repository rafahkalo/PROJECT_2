<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Driver extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    protected $table = 'drivers';
    public $timestamps = true;
    protected $fillable = [
        'office_id',
        'address',
        'status',
        'image_agency',
        'date_of_birth',
        'image_driver',
        'last_name',
        'first_name',
        'email',
        'password',
        'phoneOne',
        'phoneTwo',
        'device_key',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'driver_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'driver_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function external__travels()
    {
        return $this->hasMany(External_Travel::class);
    }


    public function evaluation()
    {
        return $this->hasMany(Evaluation::class);

    }
    public function point()
    {
        return $this->hasMany(Point::class);

    }
}