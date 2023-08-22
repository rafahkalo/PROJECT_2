<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\wallet_admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        
      $inn=  User::create([
    
        'firstname' =>'John',
       'lastname'=>'Doe',
        'email' =>'johndoe@example.com',
        'password' =>bcrypt('johan$$'),
        'role' =>1,
        'phoneOne'=>'0998888888',
        'phoneTwo'=>'0988777777',
        
    ]);
    wallet_admin::create([
        'code'=>'1222',
        'user_id'=>$inn->id,
        'amount'=>0,
    
    ]);        
    
    }
}