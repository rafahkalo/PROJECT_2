<?php

namespace App\Http\Controllers;
use App\Models\ExternalReservation;
use App\Models\External_Travel;
use App\Models\Seat;
use App\Models\PaymentFatora;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExternalReservations extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $reservations = ExternalReservation::where('user_id', $user->id)
            ->with(['travel', 'paymentType'])
            ->get();
        return response()->json(['data' => $reservations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(External_Travel $external_Travel, Request $request)
    {

        $user = Auth::user();
        $request->validate([
            'seats' => 'required|array',
            'userAccept' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $totalCost = count($request->seats) * $external_Travel->cost;
            $seatsToBook = $external_Travel->seats()->whereIn('id', $request->seats)
                ->whereNull('user_id')
                ->get();


            if ($seatsToBook->count() !== count($request->seats)) {
                return response()->json(['message' => 'Some seats are already booked'], 409);
            }
            if ($request->userAccept == 'true') {
                $seatsToBook->each(function ($seat) use ($user) {
                    $seat->update(['user_id' => $user->id]);
                });


                $reservation = ExternalReservation::create([
                    'user_id' => $user->id,
                    'travel_id' => $external_Travel->id,
                    'number_of_persons' => count($request->seats),
                    //'paymentfatora_id' => 'null',
                    'cost' => $totalCost,
                ]);

                DB::commit();

                return response()->json(['information reservation :' => $reservation], 200);
            } else {
                DB::rollBack();
                return response()->json(['message ' => 'The user did not accept Good luck'], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred.'], 500);
        }

    }
    public function reservation_sure(ExternalReservation $externalReservation, $paymenFatora)
    {
        $user = Auth::user();

        if ($paymenFatora == null)
            return response()->json(['message' => 'not booking'], 401);

        $Fatora = PaymentFatora::where('id', $paymenFatora)->first();

        if (!$Fatora) {
            return response()->json(['message' => 'fail booking becuse is not payment'], 200);
        }


        $seatsToBook = $externalReservation->travel->seats()
            ->where('user_id', $user->id)
            ->get();

        if ($seatsToBook->count() < $externalReservation->number_of_persons) {
            $externalReservation->travel->seats()->where('user_id', $user->id)->update(['user_id' => DB::raw('NULL')]);
            $externalReservation->delete();
            return response()->json(['message' => '!!You have exceeded the specified time to complete the reservation process'], 400);
        }


        if ($Fatora->paymentType->payment_type == 'elctronic' && $Fatora->is_payment) {
            $externalReservation->paymentfatora_id = $Fatora->id;
            $externalReservation->save();
            return response()->json(['message' => ' succesful booking and typePayment elctronic'], 200);
        } elseif ($Fatora->paymentType->payment_type == 'cash') {
            $externalReservation->paymentfatora_id = $Fatora->id;
            $externalReservation->save();
            return response()->json(['message' => 'succesful booking and typePayment cash '], 200);
        } else {

            $externalReservation->travel->seats()->where('user_id', $user->id)->update(['user_id' => DB::raw('NULL')]);
            $externalReservation->delete();
            return response()->json(['message' => 'The reservation failed because the payment type was not specified and the cost was not paid '], 200);
        }
        // }


    }


    /**
     * Remove the specified resource from storage.
     */
    public function cancelUserReservation(ExternalReservation $externalReservation)
    {
        $user = Auth::user();

        if (!$externalReservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        if ($externalReservation->user_id !== $user->id) {
            return response()->json(['message' => 'You do not have permission to cancel this reservation'], 403);
        }

        $paymentFatora = $externalReservation->paymentfatora;

        // if ($paymentFatora && $paymentFatora->is_payment) {
        //     return response()->json(['message' => 'Cannot cancel a paid reservation'], 400);
        // }

        $externalReservation->travel->seats()->where('user_id', $user->id)->update(['user_id' => DB::raw('NULL')]);
        $externalReservation->delete();

        return response()->json(['message' => 'Reservation has been canceled successfully'], 200);
    }

    public function showAllUserReservations()
    {
        $user = Auth::user();

        $reservations = ExternalReservation::where('user_id', $user->id)
            ->with(['travel', 'paymentFatora', 'paymentFatora.paymentType'])
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'No external reservations found'], 404);
        }
        $now = now(); // Current time
        $upcomingReservations = [];
    
        foreach ($reservations as $reservation) {
           
            $travelDate = Carbon::parse($reservation->travel->date );
            //dd( $travelDate <= $now);
            if ($travelDate >= $now) {
               
                $upcomingReservations[] = [
                    'id' => $reservation->id,
                    'user_id' => $reservation->user_id,
                    'number_of_persons' => $reservation->number_of_persons,
                    'cost' => $reservation->cost,
                    'created_at' => $reservation->created_at,
                    'updated_at' => $reservation->updated_at,
                    'travel_date' => $reservation->travel->date,
                    'travel_time' => $reservation->travel->time,
                    'travel_destnation' => $reservation->travel->destnation,
                    'travel_location' => $reservation->travel->location,
                    'last_time_paid' => $reservation->travel->last_time_paid,
                    'is_payment' => $reservation->paymentFatora->is_payment,
                    'paymentAmount' => $reservation->paymentFatora->paymentAmount,
                    'payment_created_at' => $reservation->paymentFatora->created_at,
                    'payment_type' => $reservation->paymentFatora->paymentType->payment_type,
                ];
            }
        }
        if(!$upcomingReservations)
    
        {
            return response()->json(['message'=>'not found reservation']);
        }
        return response()->json(['Upcoming External Reservations' => $upcomingReservations]);
    }

    public function showAllOldUserReservations()
    {
        $user = Auth::user();

        $reservations = ExternalReservation::where('user_id', $user->id)
            ->with(['travel', 'paymentFatora', 'paymentFatora.paymentType'])
            ->get();

        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'No external reservations found'], 404);
        }
        $now = now(); // Current time
        $upcomingReservations = [];
    
        foreach ($reservations as $reservation) {
           
            $travelDate = Carbon::parse($reservation->travel->date );
            //dd( $travelDate <= $now);
            if ($travelDate <= $now) {
               
                $upcomingReservations[] = [
                    'id' => $reservation->id,
                    'user_id' => $reservation->user_id,
                    'number_of_persons' => $reservation->number_of_persons,
                    'cost' => $reservation->cost,
                    'created_at' => $reservation->created_at,
                    'updated_at' => $reservation->updated_at,
                    'travel_date' => $reservation->travel->date,
                    'travel_time' => $reservation->travel->time,
                    'travel_destnation' => $reservation->travel->destnation,
                    'travel_location' => $reservation->travel->location,
                    'last_time_paid' => $reservation->travel->last_time_paid,
                    'is_payment' => $reservation->paymentFatora->is_payment,
                    'paymentAmount' => $reservation->paymentFatora->paymentAmount,
                    'payment_created_at' => $reservation->paymentFatora->created_at,
                    'payment_type' => $reservation->paymentFatora->paymentType->payment_type,
                ];
            }
        }
        if(!$upcomingReservations)
    
        {
            return response()->json(['message'=>'not found reservation']);
        }
    
        return response()->json(['old External Reservations' => $upcomingReservations]);
    }


    public function showDetailsReservation(ExternalReservation $externalReservation)
    {
        $user = Auth::user();

        if ($externalReservation->user_id !== $user->id) {
            return response()->json(['message' => 'You do not have permission to view this reservation'], 403);
        }

        $reservation = ExternalReservation::where('id', $externalReservation->id)
            ->with(['travel', 'paymentFatora', 'paymentFatora.paymentType'])
            ->first();


        $DetailsReservation = [
            'id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'number_of_persons' => $reservation->number_of_persons,
            'cost' => $reservation->cost,
            'created_at' => $reservation->created_at,
            'updated_at' => $reservation->updated_at,
            'travel_destnation' => $reservation->travel->destnation,
            'travel_location' => $reservation->travel->location,
            'travel_date' => $reservation->travel->date,
            'travel_time' => $reservation->travel->time,
            'last_time_paid' => $reservation->travel->last_time_paid,
            'office_name' => $reservation->travel->office->name,
            'office_location' => $reservation->travel->office->location,
            'office_branch_name' => $reservation->travel->office->branch->name,
            'office_branch_goverment' => $reservation->travel->office->branch->goverment->name,
            'driver_name ' => $reservation->travel->driver->first_name . ' ' . $reservation->travel->driver->last_name,
            'driver_phoneTwo' => $reservation->travel->driver->phoneTwo,
            'driver_phoneOne' => $reservation->travel->driver->phoneOne,
            'driver_image' => $reservation->travel->driver->image_driver,
            'is_payment' => $reservation->paymentFatora->is_payment,
            'paymentAmount' => $reservation->paymentFatora->paymentAmount,
            'payment_created_at' => $reservation->paymentFatora->created_at,
            'payment_updated_at' => $reservation->paymentFatora->updated_at,
            'payment_type' => $reservation->paymentFatora->paymentType->payment_type,
        ];


        return response()->json(['DetailsReservation' => $DetailsReservation]);
    }


    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $unpaidReservations = ExternalReservation::whereNull('paymentfatora_id')
                ->where('created_at', '<=', now()->subMinutes(10))
                ->get();

            foreach ($unpaidReservations as $reservation) {
                $reservation->travel->seats()->where('user_id', $reservation->user_id)->update(['user_id' => DB::raw('NULL')]);
                $reservation->delete();
            }
        })->everyTenMinutes();
    }


}