<?php

namespace Database\Seeders;
use App\Models\Communal;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommunalSeeder extends Seeder
{

    public function run(): void
    {
        $gov1= Communal::create([
            'amount_communal' =>20000,
           'communal_period'=>365,
        ]);
       

    }
}
