<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table='favorites';
    protected $primaryKey ="id";
    public $timestamps=true;


    protected $fillable=[

        'user_id',
        'star_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function star()
    {
        return $this->belongsTo(Star::class);
    }
}
