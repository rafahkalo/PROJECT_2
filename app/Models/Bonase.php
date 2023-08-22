<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonase extends Model
{
    use HasFactory;
    protected $table='bonase';
    
    public $timestamps=true;
    
    protected $fillable=
    [
        
        'point_id',
        'amount',
        'description',
        'office_id'
        
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
