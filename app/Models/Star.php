<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    use HasFactory;
    protected $table='stars';
    protected $primaryKey ="id";
    public $timestamps=false;


    protected $fillable=[

        'name',
        'description',
        'number'
    ];



    public function offices()
    {
     return $this->hasMany(Office::class);

    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class,'star_id');
    }
}
