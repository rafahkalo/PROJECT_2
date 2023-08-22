<?php

namespace App\Http\Controllers;
use App\Models\Office;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */



     public function AddReward(Request $request)
     {
         $office_id = Auth::id();
     
         $rules = [
             'amount' => 'required',
             'num_point' => 'required|string',
             
         ];
     
     
     
         $Reward = new Reward();
         $Reward->amount = $request->amount;
         $Reward->num_point = $request->num_point;
         $Reward->office_id = $office_id;

         $Reward->save();
     
         return response()->json(['message' => 'Reward added successfully'], 200);
     }
     

     public function UpdateReward(Request $request)
     {
 
         $id= Auth::id();
   $office=Office::where('id',$id)->get()->first();
   
        $input=$office->id;
        $raward=Reward::where('office_id',$input)->update($request->all());
        
    return response()->json(['data'=>$raward,'message'=> "updated", 200]);
    }
     
 

    public function ShowAllReward()
    {
      $id=Auth::id();
       $data = Reward::where('office_id',$id)->get()->first();
       return  response()->json($data,200);


    }


    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reward $reward)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reward $reward)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        //
    }
}
