<?php

namespace App\Http\Controllers;

use App\Models\ExternalReservation;
use App\Models\PaymentFatora;
use App\Models\Payment_Type;
use App\Models\Wallet_admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class paymentFatoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentFatoras = PaymentFatora::all(); // Retrieve all payment fatora records

        return response()->json(['data' => $paymentFatoras], 200); // Return the records as JSON response

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'is_payment' => 'required|boolean',
            'payment_id' => 'required',
            'user_id' => 'required',
            'paymentAmount' => 'required|numeric',
        ]);

        $paymentFatora = PaymentFatora::create([
            'is_payment' => $request->input('is_payment'),
            'payment_id' => $request->input('payment_id'),
            'user_id' => $request->input('user_id'),
            'paymentAmount' => $request->input('paymentAmount'),
        ]);

        return response()->json(['message' => 'Payment Fatora created successfully', 'data' => $paymentFatora], 201);

    }

    public function paymentMethod($Reservation,Request $request){
        $Reservation= ExternalReservation::where('id',$Reservation)->first();
        
        if(!$Reservation)
        return response()->json(['message' => 'you do not have any reservation']);
        $typePayment=$request->typePayment;
        
        $type=Payment_Type::where('payment_type',$typePayment)->first();
       
    if($type->payment_type =='cash')
    {
        $paymentFatora = PaymentFatora::create([
            'payment_id' => $type->id,
            'user_id' => Auth::user()->id,
            'paymentAmount' =>  $Reservation->cost,
        ])->first();
      
        $Reservation->paymentfatora_id = $paymentFatora->id;
        $Reservation->save();
       
        return response()->json(['message' => 'Payment cash successfully', ' Payment information' => $paymentFatora ], 201);

    }
    elseif($type->payment_type =='electronic')
    {
        $walletUser=Wallet_admin::where('user_id', Auth::user()->id)->first();
       
        if(!isset($walletUser))
    {
        return response()->json(['message' => 'you dont have wallet,please enter card']);
    }
       
        
        
        if($walletUser->amount >= $Reservation->cost)
        {
            $new=$walletUser->amount - $Reservation->cost;
            Wallet_admin::where('user_id', Auth::user()->id)->update(['amount'=>$new]);
           
            $wallet_office=$Reservation->travel->office->wallets()->first();
          //  dd($wallet_office->amount);
            $old=$wallet_office->amount;
            
            $new= $old + $Reservation->cost;
            $wallet_office->amount=$new;
            $wallet_office->save();
            
            $paymentFatora = PaymentFatora::create([
                'payment_id' => $type->id,
                'user_id' => Auth::user()->id,
                'is_payment'=> 1,
                'paymentAmount' =>  $Reservation->cost,
            ]);
            $Reservation->paymentfatora_id = $paymentFatora->id;
        $Reservation->save();
            return response()->json(['message' => 'Payment electronic successfully', ' Payment information' => $paymentFatora ], 201);

        }
        else{
            return response()->json(['message' => 'the amount paid is insufficient.
            Try again by paying the correct amount']);
        }
       
    }




    }

}