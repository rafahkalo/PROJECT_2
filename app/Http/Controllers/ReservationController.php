<?php

namespace App\Http\Controllers;

use App\Models\Box_Reservation;
use App\Models\Personal_Reservation;
use App\Models\Reservation;
use App\Models\Driver;
use App\Models\NotificationDriver;
use App\Models\NotificationUser;
use App\Models\User;
use App\Models\Wallet_admin;
use App\Models\Wallet_Office;
use Illuminate\Http\Request;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    use NotificationTrait;
    public function __construct()
    {

        $this->middleware('auth:sanctum');

    }

    /**
     * Display a listing of the resource.
     */
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




    // ...

    public function store(Request $request)
    {
        $rules = [
            'driver_id' => 'required',
            'date' => 'required',
            'travel_time' => 'required',
            'goal' => 'required',
            'location' => 'required',
            'reservation_type' => 'required|in:personal,box',
            'content' => 'required_if:reservation_type,box',

            'num_person' => 'required_if:reservation_type,personal',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $reservation = Reservation::create([
            'goal' => $request->goal,
            'location' => $request->location,
            'driver_id' => $request->driver_id,
            'user_id' => Auth::user()->id,
            'date' => $request->date,
            'travel_time' => $request->travel_time,
        ]);


        if ($request->reservation_type == 'personal') {
            Personal_Reservation::create([
                'num_person' => $request->num_person,
                'res_id' => $reservation->id,
            ]);
        } elseif ($request->reservation_type == 'box') {
            Box_Reservation::create([
                'content' => $request->content,
                'res_id' => $reservation->id,
            ]);
        }

       $namedriver=Driver::where('id',$request->driver_id)->pluck('first_name')->first();
       $message="You  Have New Reservation from"."  ".auth()->user()->firstname;
       $title="Hello ".$namedriver."  New Notification";
       $driverToken=Driver::where('id',$request->driver_id)->pluck('device_key')->first();
       $notification=$this->send($driverToken,$message,$title);
if(! is_null($notification)){
   NotificationDriver::create([
       "title"=>$title,
       "message"=>$message,
       "driver_id"=>$request->driver_id

   ]);


}

       return response()->json(["message" => "Reservation created successfully",
      "notification info"=>$notification,

   ], 201);

    }

    public function acceptReservation($id)
    {

        $reservation = Reservation::find($id);
        if ($reservation) {
            Reservation::where('id', $id)->update(['status' => true]);
            $user_id=Reservation::where('id', $id)->pluck('user_id')->first();
        $userToken=User::where('id', $user_id)->pluck('device_key')->first();
        $userName=User::where('id', $user_id)->pluck('firstname')->first();
        $message="Accepted the trip";
        $title="Hello  ".$userName;
        $notification=$this->send($userToken,$message,$title);

        NotificationUser::create([
            "title"=>$title,
            "message"=>$message,
            "user_id"=> $user_id

        ]);
            return response()->json(['message' => "Accept this order",
            "notification info"=>$notification], 200);
        } else {
            return response()->json(['message' => "There Are wrong in this order", 200]);

        }


    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {

        $reservation = Reservation::find($id);
        //dd( $reservation);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservationType = null;
        $reservationData = null;
        $reservationAttributes = [
            'driver_id',
            'user_id',
            'status',
            'date',
            'travel_time',
            'location',
            'goal'
        ];

        // Extract the specified reservation attributes
        $reservationDetials = $reservation->only($reservationAttributes);

        if ($reservation->personalReservations->isNotEmpty()) {

            $reservationType = 'personal';

            $reservationData = $reservation->personalReservations->first();
        } elseif ($reservation->boxReservations->isNotEmpty()) {
            $reservationType = 'box';

            $reservationData = $reservation->boxReservations->first();
        }

        if (!$reservationType || !$reservationData) {
            return response()->json(['message' => 'No matching reservation type found'], 404);
        }

    //  $responseData = [

    //     'reservationDetials' => $reservationDetials,
    //     'reservation_type' => $reservationType,
    // ];

    // Add specific details based on reservation type
    if ($reservationType === 'box') {
        $responseData['Box_content'] = $reservationData->box_content;
    } elseif ($reservationType === 'personal') {
        $responseData['num_person'] = $reservationData->num_person;
    }

    // Return the response data in a JSON response
    return response()->json(['reservationDetials'=> $reservationDetials,
    'reservation_type' => $reservationType], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function startTrip($id)
    {
        $reservation = Reservation::find($id);
        $status = $reservation->status;


        if ($status == 1) {
            Reservation::where('id', $id)->update(['finish' => true]);

            return response()->json(['message' => "Start this trip", 200]);
        } else {
            return response()->json(['message' => "This Trip is not accepted", 200]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function searchByTime(Request $request)
    {
        $reservation = Reservation::where('travel_time', $request->time)->get();

        return response()->json(['data' => $reservation, 200]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $reservation = Reservation::find($id);
        $user_id=Reservation::where('id', $id)->pluck('user_id')->first();
        $userToken=User::where('id', $user_id)->pluck('device_key')->first();
        $userName=User::where('id', $user_id)->pluck('firstname')->first();
        $message="Not Accepted Trip Try Again";
        $title="Hello  ".$userName;
        $notification=$this->send($userToken,$message,$title);

        NotificationUser::create([
            "title"=>$title,
            "message"=>$message,
            "user_id"=> $user_id

        ]);

        $reservation->delete();



        return response()->json(["message" => "Deleted successfuly",
            "notification"=>$notification
    ], 200);
}

public function payInternal(Request $request){

        $rules=[
            'office_id'=>'required',
            'cost'=>'required'
        ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json(["errors" => $validator->errors()], 400);
                }
                $walletuser=Wallet_admin::where('user_id',auth()->user()->id)->pluck('amount')->first();
                $walletoffice=Wallet_Office::where('office_id',$request->office_id)->pluck('amount')->first();
                if($walletuser>= $request->cost){
                    $all=$walletuser-$request->cost;
                    $allOffice=$walletoffice+$request->cost;
                    Wallet_admin::where('user_id',auth()->user()->id)->update(['amount'=>$all]);

                   Wallet_Office::where('office_id',$request->office_id)->update(['amount'=>$allOffice]);
                 return response()->json(['message'=>"You Paid Successfully" , 200]);

            }
            else{


                return response()->json(['message'=>"You Don't have Enough" , 200]);

            }   }
}