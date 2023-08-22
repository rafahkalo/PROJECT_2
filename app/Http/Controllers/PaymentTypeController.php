<?php

namespace App\Http\Controllers;

use App\Models\Payment_Type;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Types= Payment_Type::get();
    
        return response()->json($Types, 200);
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|string', // Adjust validation rules as needed
        ]);

        $paymentType = Payment_Type::create([
            'payment_type' => $request->input('payment_type'),
        ]);

        return response()->json(['message' => 'Payment Type created successfully', 'data' => $paymentType], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment_Type $payment_Type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment_Type $payment_Type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment_Type $payment_Type)
    {
        //
    }
}
