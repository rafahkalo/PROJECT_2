<?php

namespace App\Http\Controllers;

use App\Models\NotificationUser;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class NotificationUserController extends Controller
{
use NotificationTrait;

public function __construct()
{

    $this->middleware('auth:sanctum');

}
public function index()
{
    $noti=NotificationUser::where('user_id',Auth::id())->get();
    
    return response()->json([
        'data' =>$noti 
    ]);
}

}
   