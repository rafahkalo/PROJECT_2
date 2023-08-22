<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_travel extends Model
{
    use HasFactory;
    protected $table='type_travels';
    
    public $timestamps=true;
     
   
    protected $fillable=[
        
        'name',
    
    ];
    
    public function office()
    {
     return $this->hasMany(Office::class);
 
    }
    public function point()
    {
        return $this->belongsTo(Point::class);
    }   
}
