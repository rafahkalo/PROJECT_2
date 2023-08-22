<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint_External extends Model
{
    use HasFactory;
    
    protected $table='complaint__externals';
    public $timestamps=true;
    protected $fillable = ['user_id','ex_travel_id','text'];
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
  /*  public function external_res()
    {
        return $this->belongsTo(User::class);
    }*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}