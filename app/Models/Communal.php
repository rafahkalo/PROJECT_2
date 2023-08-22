<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communal extends Model
{
    use HasFactory;   
    protected $table='communal';
    public $timestamps=true;
    protected $fillable = ['amount_communal','communal_period'];
}