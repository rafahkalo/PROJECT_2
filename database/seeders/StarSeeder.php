<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Star;
class StarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Star::create([
            'name'=>'Five Stars',
            'description'=>'xxxxx',
            'number'=> 5

        ]);
        Star::create([
            'name'=>'Four Stars',
            'description'=>'xxxx',
            'number'=> 4

        ]);
        Star::create([
            'name'=>'three Stars',
            'description'=>'xxx',
            'number'=> 3

        ]);
        Star::create([
            'name'=>'two Stars',
            'description'=>'xx',
            'number'=> 2

        ]);
        Star::create([
            'name'=>'one Stars',
            'description'=>'x',
            'number'=> 1

        ]);
    }
}