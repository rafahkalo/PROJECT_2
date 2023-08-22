<?php

namespace App\Http\Controllers;

use App\Models\Star;
use Illuminate\Http\Request;


class StarController extends Controller
{


  public function addStars(Request $request)
  {

    $input = $request->validate([

      'name' => 'required|string',
      'number' => 'required|numeric'
    ]);

    $data = new Star();
    $data->name = $request->name;
    $data->number = $request->number;
    $data->save();

    return response()->json(['message' => 'stars save successfully'], 200);

  }



  public function showStars()
  {
    $stars = Star::get();
    return response()->json($stars, 200);

  }
}