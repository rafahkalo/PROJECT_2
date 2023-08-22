<?php

namespace App\Http\Controllers;

use App\Models\NotificationDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationDriverController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:sanctum');

    }
    public function index()
    {
        $noti=NotificationDriver::where('driver_id',Auth::id())->get();

        return response()->json([
            'data' =>$noti
        ]);
    }

}