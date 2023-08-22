<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offire extends Model
{
    use HasFactory;
    protected $table='offer';
    
    public $timestamps=true;
     
  
    protected $fillable=[
        'description',
        'office_id',

        
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

}
