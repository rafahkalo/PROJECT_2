<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table='branches';
    
    public $timestamps=true;
     
   
    protected $fillable=
    [
        
        'name',
        'gov_id',
        
    ];



    public function office()
    {
     return $this->hasMany(Office::class,'branch_id');
 
    }
         
 
    public function goverment()
    {
        return $this->belongsTo(Goverments::class,'gov_id');
    }

    public function point()
    {
        return $this->belongsTo(Point::class);
    }





}