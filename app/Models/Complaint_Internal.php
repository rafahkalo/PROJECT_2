<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint_Internal extends Model
{
    use HasFactory;
    
    protected $table='complaint__internals';
    protected $fillable = ['user_id','res_id','text'];

    public $timestamps=true;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function res()
    {
        return $this->belongsTo(Reservation::class);
    }
}