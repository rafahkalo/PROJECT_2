<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal_Reservation extends Model
{
    use HasFactory;
    protected $table = 'personal__reservations'; // Specify the table name

    protected $primaryKey = 'id'; // Specify the primary key column name
    public $timestamps = true; 
    protected $fillable = [
        'num_person',
        'res_id',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'res_id');
    }
}
