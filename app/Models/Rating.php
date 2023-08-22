<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment','rating','user_id','driver_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }
}