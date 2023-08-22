<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorCarRequestr;
use App\Models\Car;
use App\Models\Office;

use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use App\Models\Car_type;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController as FileController;
use Illuminate\Support\Facades\Validator;

class CarController extends FileController
{

    public function AddCarbyOffice(Request $request)
    {
        $office = Auth::id();

        $rules = [
            'type_id' => 'required',
            'color_id' => 'required|string',
            'number' => 'required',
            'numberOfSeatin' => 'required',
            'agency_image' => 'required',
            'image' => 'required',
            'cost' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => 'There is a missing field'], 500);
        }

        $photo = $this->saveFile($request, 'image', public_path('/uploads'));
        $photoo = $this->saveFile($request, 'agency_image', public_path('/uploads'));

        $car = Car::create([
            'image' => $photo,
            'agency_image' => $photoo,
            'office_id' => $office,
            // use the ID of the office
            'type_id' => $request->type_id,
            'color_id' => $request->color_id,
            'number' => $request->number,
            'numberOfSeating' => $request->numberOfSeatin,

            'cost' => $request->cost,
        ]);

        return response()->json(['message' => 'Car save successfully'], 200);
    }




    public function AddCarbydriver(Request $request)
    {
        $driver_id = Auth::id();


        $rules = [
            'type_id' => 'required',
            'color_id' => 'required|string',
            'number' => 'required',
            'agency_image' => 'required',
            'image' => 'required',
            'cost' => 'required',
            'numberOfSeatin' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $car = new Car();
        $car->type_id = $request->type_id;
        $car->color_id = $request->color_id;
        $car->number = $request->number;
        $car->numberOfSeating = $request->numberOfSeatin;

        $car->agency_image = $request->agency_image;
        $car->image = $request->image;
        $car->cost = $request->cost;
        $car->office_id = $request->office_id; // assuming office_id is set on the Driver model
        $car->driver_id = $driver_id;
        $car->save();

        return response()->json(['message' => 'Car added successfully'], 200);
    }


    public function updateCardriverbyOffice(Request $request, $id)
    {


        // Retrieve the Car model instance by its ID
        $car = Car::find($id);

        // Update the driver_id property with the new value
        $x = $request->driver_id;
        if (!Driver::find($x)) {
            return response()->json(['message' => 'Invalid driver ID'], 400);
        }
        $car->driver_id = $x;
        // Save the changes to the database
        $car->save();

        return response()->json(['message' => 'Driver ID updated successfully'], 200);

    }



    public function updateCarbyOffice(Request $request, $id)
    {


        $input = Car::find($id);

        if (isset($input)) {
            $photo = $this->saveFile($request, 'agency_image', public_path('public/uploads/'));
            $photo2 = $this->saveFile($request, 'image', public_path('public/uploads/'));

            $input->image = $photo2;
            $input->cost = $request->cost;
            $input->agency_image = $photo;
            $input->color_id = $request->color_id;
            $input->type_id = $request->type_id;
            $input->update();


            return response()->json(['message' => 'Car update successfully'], 200);
        }

        return response()->json(['message' => 'not found car'], 401);
    }


    public function showcolor()
    {
        $color = Color::get();
        return response()->json($color, 200);

    }


    public function showAllCarsToOffice()
    {
        $id = Auth::id();

        $externalTravels = Car::where('office_id', $id)
            ->join('car_types', 'cars.type_id', '=', 'car_types.id')
            ->join('colors', 'cars.color_id', '=', 'colors.id')
            ->select('cars.*', 'car_types.name as type_name', 'colors.name as color_name')
            ->get();

        return response()->json($externalTravels, 200);
    }


    public function showCar_type()
    {
        $carType = Car_type::get();
        return response()->json($carType, 200);

    }

    public function getIdCar($id)
    {
        $car1 = Car::find($id);
        $car = Car::where('id', $id)->get();

        $color = $car1->color_id;
        $colors = Color::where('id', $color)->get()->first();

        $type = $car1->type_id;
        $types = Car_type::where('id', $type)->get()->first();
        $office = $car1->office_id;
        $offices = Office::where('id', $office)->get()->first();

        $driver = $car1->driver_id;
        $drivers = Driver::where('id', $driver)->get()->first();

        $data = [
            'car' => $car,
            'color' => $colors->name,
            'type' => $types->name,
            'driverfirst' => $drivers->first_name,
            'driverlast' => $drivers->last_name
        ];
        return response()->json($data, 200);
    }

}