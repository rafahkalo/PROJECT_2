<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    public $timestamps=true;
    protected $table='rewards';

    protected $fillable = ['office_id','num_point','amount'];

    
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

}
