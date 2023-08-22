<?php

namespace App\Http\Controllers;

use App\Models\External_Travel;
use App\Models\Type_travel;
use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Car;
use App\Models\Seat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\FavoriteRepository;
use Illuminate\Support\Facades\DB;

class ExternalTravelController extends Controller
{
    ///Tasneem////
    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function show_available_Travels()
    {
        $userId = auth()->id();
        $favoriteStarIds = $this->favoriteRepository->getFavoriteStarIdsByUser($userId);

        $now = now();
        $externalTravels = External_Travel::join('offices', 'external_travel.office_id', '=', 'offices.id')
            ->leftJoin('drivers', 'external_travel.driver_id', '=', 'drivers.id')
            ->whereIn('offices.star_id', $favoriteStarIds)
            ->where('external_travel.status', 0)
            ->where('external_travel.date', '>=', $now->toDateString())
            ->select(
                'external_travel.id',
                'external_travel.date',
                'external_travel.time',
                'external_travel.destnation',
                'external_travel.location',
                'external_travel.cost',
                'external_travel.status',
                'external_travel.last_time_paid',
                'external_travel.office_id',
                'external_travel.driver_id',
                'offices.name as office_name',
                \DB::raw("CONCAT(drivers.first_name, ' ', drivers.last_name) as driver_name"),
                // Concatenate full name
                'drivers.phoneOne  as driver_phoneOne',
                'drivers.phoneTwo  as driver_phoneTwo'
            )->with([
                    'seats' => function ($query) {
                        $query->where('user_id', null); // Check if user_id is NOT null for any seat
                    }
                ])
            ->get();
         

        $filteredTravels = $externalTravels->filter(function ($travel) {
            return !$travel->seats->isEmpty();
        });

        if ($filteredTravels->isEmpty()) {
            return response()->json(['message' => 'No available external travels'], 404);
        }
        $filteredTravelsWithoutSeats = $filteredTravels->map(function ($travel) {
            unset($travel->seats);
            return $travel;
        });

        return response()->json(['travels' => $filteredTravelsWithoutSeats], 200);
    }





    public function addExternal_Travel(Request $request)
    {
        

        $office_id = Auth::id();
        $rules = [
            'driver_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'time' => ['required', 'regex:/^\d{1,2}:\d{2}$/'],
            'destnation' => 'required|string',
            'cost' => 'required',
            'location' => 'required',
            'last_time_paid' => ['required', 'regex:/^\d{1,2}:\d{2}$/'],
        ];
       


        $validator = Validator::make($request->all(), $rules);
       

        try {

            DB::beginTransaction();
            $driverId = $request->driver_id;
            $external_Travel = External_Travel::create([

                'driver_id' => $driverId,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,

                'destnation' => $request->destnation,
                'cost' => $request->cost,
                'office_id' => $office_id,
                'last_time_paid' => $request->last_time_paid,

            ]);
            

            ////TASNEEM
            $car = Car::where('driver_id', $driverId)->first();
            

            for ($seatNumber = 1; $seatNumber <= $car->numberOfSeating; $seatNumber++) {
                Seat::create([
                    'travel_id' => $external_Travel->id,
                    'numberOfSeat' => $seatNumber,

                ]);
            }

            /////

            DB::commit();
            return response()->json(['message' => 'External_Travel save successfully'], 200);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(['message' => 'Failed to add External_Travel '], 500);
        }
    }

    public function getexternaltravelTooffice($id)
    {
        $travelx = Office::find($id);
        $travel = Office::find($id)->external_travels()->get();

        $travels = [

            'office' => $travelx->name,
            'Externel' => $travel
        ];
        return response()->json($travels, 200);
    }

    public function showTravelStatusTrue()
    {
        $id = Auth::id();

        $externalTravels = External_Travel::where('office_id', $id)->
            where('status', "true")
            ->get();
        return response()->json($externalTravels, 200);
    }
    public function showTravelStatusFalse()
    {
        $id = Auth::id();

        $externalTravels = External_Travel::where('office_id', $id)->
            where('status', "0")
            ->get();
        return response()->json($externalTravels, 200);
    }


    public function getIdExternalTravel($id)
    {
        $travel = External_Travel::find($id);
        $travel1 = External_Travel::where('id', $id)->get();
        $office = $travel->office_id;
        $x = Office::where('id', $office)->get()->first();
        $drivers = $travel->driver_id;
        $xx = Driver::where('id', $drivers)->get()->first();
        $travels = [
            'travel' => $travel1,
            'name_office' => $x->name,
            'firstName' => $xx->first_name,
            'Last_Name' => $xx->last_name
        ];
        return response()->json($travels, 200);
    }

    public function showExterneal()
    {

        $data = External_Travel::get();
        $data['message'] = 'successfull';
        return response()->json($data, 200);


    }

}