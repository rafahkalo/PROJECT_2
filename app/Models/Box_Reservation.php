<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box_Reservation extends Model
{
    use HasFactory;
    protected $table = 'box__reservations'; 

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'content',
        'res_id',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'res_id');
    }
}