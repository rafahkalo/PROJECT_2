<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\external_Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show_available_seating_oneTravel(External_Travel $external_Travel)
    {
        $availableSeats = $external_Travel->seats()->where('user_id', null)->get();

        if ($availableSeats->isEmpty()) {
            $external_Travel->update(['is_available' => 'fulse']);
            return response()->json(['message' => 'Sorry, but there are no seats available to show '], 200);

        } else {
            return response()->json(['available_seats' => $availableSeats], 200);
        }
    }



    public function show_seating_oneTravel(External_Travel $external_Travel)
    {
        $Seats = $external_Travel->seats();

        return response()->json(['Seats' => $Seats], 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function modifySeatInfoByOffice(External_Travel $external_Travel, Seat $seat)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $external_Travel->office_id) {
            return 'You are not authorized to modify this seat';
        }

        // Update seat 
        if ($seat->user_id == null) {
            $seat->update(['user_id' => $external_Travel->office_id]);
            return response()->json(['message' => 'Seat information updated successfull '], 200);

        } else {
            return response()->json(['message' => 'this seat is booking '], 200);
        }



    }
    public function check_pre_booking(External_Travel $external_Travel, Request $request)
    {
        $user = Auth::user();
        $request->validate([
         'seats' => 'required|array',
           //'seats' => 'required'
        ]);
        // $seats = json_decode($request->input('seats')); 

        $seatsToBook = Seat::whereIn('id', $request->seats)
      // $seatsToBook = Seat::whereIn('id', $seats)
            ->whereNull('user_id')
            ->where('travel_id', $external_Travel->id)
            ->get();

        if ($seatsToBook->count() !== count($request->seats)) {
            return response()->json(['message' => 'Sorry, it is not available'], 409);
        }
        $totalCost = count($request->seats) * $external_Travel->cost;

        return response()->json([
            'message' => 'it is available',
            'totalCost' => $totalCost
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seat $seat)
    {
        //
    }

}