<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Complaint_External;
use App\Models\Driver;
use App\Models\External_Travel;
use App\Models\Rating;
use App\Models\Reservation;
use App\Models\Wallet_Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\Complaint_Internal;
use App\Models\Office;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:sanctum');

    }

   
    /**
     * Display a listing of the resource.
     */
    public function complaintInternals(Request $request)
    {
        $rules = [
            'res_id' => 'required',
            'text' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $res2 = Reservation::where('id', $request->res_id)->first();
        ;
        if (is_null($res2)) {

            return response()->json(["message" => "This reservation not found "], 200);
        }

        $res = Reservation::find($request->res_id);

        if ($res->finish == '1') {
            Complaint_Internal::create([
                'text' => $request->text,
                'res_id' => $request->res_id,
                'user_id' => auth()->user()->id
            ]);

            return response()->json(["message" => "Thanks for your Complaint"], 201);
        } else {
            return response()->json(["message" => "This reservation not finish yet"], 200);
        }
    }

    public function complaintExternal(Request $request)
    {
        $rules = [
            'ex_travel_id' => 'required',
            'text' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $res2 = External_Travel::where('id', $request->ex_travel_id)->first();
        ;
        if (is_null($res2)) {

            return response()->json(["message" => "This reservation not found "], 200);
        }

        if ($res2->status == '1') {
            Complaint_External::create([
                'text' => $request->text,
                'ex_travel_id' => $request->ex_travel_id,
                'user_id' => auth()->user()->id
            ]);

            return response()->json(["message" => "Thanks for your Complaint"], 201);
        } else {
            return response()->json(["message" => "This reservation not finish yet"], 200);
        }
    }


    public function searchByNameOffice(Request $request)
    {

        $office = Office::with(['branch.goverment', 'type_travel', 'stars'])->where('name', $request->name)->get();
        if (!$office) {
            return response()->json(['message' => 'Office not found'], 404);
        }


        return response()->json(['Office' => $office], 200);


    }

    public function searchByBranchOffice(Request $request)
    {
        $branch = Branch::with(['office', 'office.type_travel', 'office.stars'])->where('name', $request->name)->get();
        if (is_null($branch)) {
            return response()->json(['message' => 'branch not found'], 404);
        } else {
            return response()->json(['branch' => $branch], 200);
        }

    }
   
    /**
     * Display the specified resource.
     */
    public function profile(Request $request)
    {
        $user=auth()->user();
        if ($user->profileImage) {
            $profile_image_url = asset('uploads/'.$user->profileImage);
        }
       

        return response()->json(['info' => auth()->user(),
        'profile_image_url' => $profile_image_url ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Re
     * move the specified resource from storage.
     */
    public function showOneTrip($id)
    {
        $trip = Reservation::with('driver')->where('user_id', auth()->user()->id)->where('id', $id)->get();

        return response()->json(['data' => $trip], 200);
    }
    

    public function storeRating(Request $request)
    {
        //
        $rules = [
            'rating' => ['required', 'in:1,2,3,4,5'],
            'comment' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->messages();
        }
        Rating::create([
            'user_id' => auth()->user()->id,
            'driver_id' => $request->driver_id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);
        return response()->json(["message" => "Welcom with you"], 201);

    }

    public function showAllOfficesAccept()
    {
        $offices = Office::where('status', '1')->get();
        return response()->json(['AllOffices' => $offices], 200);
    }
    public function showMyTripInternal()
    {
        $All = Reservation::where([['user_id', auth()->user()->id], ['status', '1']])->get();

        return response()->json(['AllTripsInternal' => $All], 200);


    }
    public function showAllDriversAccept()
    {
        $Driver = Driver::where('status', '1')->get();
        return response()->json(['AllDriver' => $Driver], 200);
    }
    
    public function addWalletUser(Request $request){
        $request->validate([
            'code' => 'required',
            'amount' => 'required'
        ]);
        Wallet_admin::create([
            'code'=>$request->code,
        'amount'=>$request->amount,
            'user_id'=>auth()->user()->id
            
        ]);

        return response()->json([
        "message"=>"added your wallet"], 201);
    }

}