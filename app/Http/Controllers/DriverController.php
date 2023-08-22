<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use App\Models\Office;
use App\Models\Car;
use App\Models\Reservation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\FileController as FileController;

class DriverController extends FileController
{
    public function showAllDriversnotAccept()
    {
        $Driver = Driver::where('status', '0')->get();
        return response()->json(['AllDriver' => $Driver], 200);
    }

    public function JoinRequestnotcar(Request $request)
    {
        $rules = [
            'address' => 'required',
            'date_of_birth' => 'required',
            'image_driver' => 'required',
            'image_agency' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required',
            'phoneOne' => 'required|string|regex:/^09[0-9]{8}$/',
            'phoneTwo' => 'required|string|regex:/^09[0-9]{8}$/',
            'office_id' => 'required',
        ];


        $image2 = $this->saveFile($request, 'image_agency', public_path('/uploads'));
        $image1 = $this->saveFile($request, 'image_driver', public_path('/uploads'));

        // Check if a driver with the same email already exists
        $existingDriver = Driver::where('email', $request->email)->first();
        if ($existingDriver) {
            return response()->json(['message' => 'A driver with this email already exists'], 409);
        }

        $driver = Driver::create([
            'office_id' => $request->office_id,
            'address' => $request->address,
            'image_driver' => $image1,
            'image_agency' => $image2,
            'date_of_birth' => $request->date_of_birth,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneOne' => $request->phoneOne,
            'phoneTwo' => $request->phoneTwo,
            'device_key' => $request->device_key,
        ]);
        return response()->json(['message', $driver], 200);

    }




    public function JoinRequestwithcar(Request $request)
    {
        //Send a joining request
        $rules = [

            'address' => 'required',
            'date_of_birth' => 'required',
            'image_driver' => 'required|image',
            'image_agency' => 'required|image',
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phoneOne' => 'required|string|regex:/^09[0-9]{8}$/',
            'phoneTwo' => 'required|string|regex:/^09[0-9]{8}$/',
            'office_id' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->messages();
        }
        $image2 = $this->saveFile($request, 'image_agency', public_path('/uploads'));

        $image1 = $this->saveFile($request, 'image_driver', public_path('/uploads'));

        $Driver = Driver::create([

            'office_id' => $request->office_id,
            'address' => $request->address,
            'image_driver' => $image1,
            'image_agency' => $image2,
            'date_of_birth' => $request->date_of_birth,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneOne' => $request->phoneOne,
            'phoneTwo' => $request->phoneTwo,
            'device_key' => $request->device_key,
        ]);
        if ($request->has('cost')) {

            $rules1 = [
                'type_id' => 'required',
                'agency_image' => 'required|image',
                'image' => 'required|image',
                'office_id' => 'required',
                'number' => 'required',
                'color_id' => 'required',
                'numberOfSeatin' => 'required',
                'cost' => 'required',

            ];


            $validator = Validator::make($request->all(), $rules1);
            if ($validator->fails()) {
                return $validator->messages();
            }
            $image1 = $this->saveFile($request, 'agency_image', public_path('/uploads/driver'));

            $image2 = $this->saveFile($request, 'image', public_path('/uploads/driver'));

            Car::create([

                'type_id' => $request->type_id,
                'agency_image' => $image1,
                'office_id' => $request->office_id,
                'number' => $request->number,
                'color_id' => $request->color_id,
                'driver_id' => $Driver->id,
                'cost' => $request->cost,
                'numberOfSeating' => $request->numberOfSeatin,
                'image' => $image2,

            ]);



            return response()->json(['message', $Driver], 201);



        }
    }


    public function AcceptDriver($id)
    {

        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        $driver->update([
            'status' => true,
        ]);

        return response()->json([
            'message' => 'Driver accepted successfully',
            'info office' => $driver
        ], 200);

    }
    public function loginDriver(Request $request)
    {


        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);


        $driver = Driver::where('email', $fields['email'])->first();


        if (!$driver || !Hash::check($fields['password'], $driver->password)) {
            return response([
                'message' => 'Password is worng or email'
            ], 401);
        }
        $token = $driver->createToken('ghaidaa')->plainTextToken;

        Driver::where('id',$driver->id)->update(['device_key'=>$request->device_key]);
        $response = [
            'driver' => $driver,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function RefuseDriver($id)
    {

        Driver::find($id)->delete();
        return response()->json([
            'message' => 'Cancel this Driver',
        ], 200);
    }

    public function Showonedriver($id)
    {
        $driver = Driver::find($id);

        if ($driver) {
            $data = [
                'id' => $driver->id,
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'image_driver' => $driver->image_driver,
                'date_of_birth' => $driver->date_of_birth,
                'email' => $driver->email,
                'phoneOne' => $driver->phoneOne,
                'phoneTwo' => $driver->phoneTwo,
                'address' => $driver->address,
                'image_agency' => $driver->image_agency,
                'message' => 'Successfully retrieved driver information'
            ];

            if ($driver->image_driver) {
                $data['image_driver_url'] = asset('uploads/' . $driver->image_driver);
            }
            if ($driver->image_agency) {
                $data['image_agency_url'] = asset('uploads/' . $driver->image_agency);
            }

            return response()->json($data, 200);
        }

        $response = [
            'message' => 'Driver not found'
        ];

        return response()->json($response, 404);
    }


    public function ShowAlldriver()
    {
        $id = Auth::id();
        $drivers = Driver::where('office_id', $id)
            ->with('office:id,name')
            ->get()
            ->map(function ($driver) {
                $driver['office_name'] = $driver->office->name;
                unset($driver->office);
                return $driver;
            })
            ->toArray();
        return response()->json($drivers, 200);
    }

    public function ToDoTravels()
    {
        $id = Auth::id();
        $todotravels = Driver::with('reservations')->find($id);

        return response()->json(['data' => $todotravels, 'message' => "ok", 200]);
    }

    public function FinishTravels()
    {
        $id = Auth::id();
        $todotravels = Driver::with([
            'reservations' => function ($q) {
                $q->where([['status', '1'], ['finish', '1'],])->get();


            }
        ])->find($id);

        return response()->json(['data' => $todotravels, 'message' => "ok", 200]);
    }



    public function finishonetravel($id)
    {

        $res = Reservation::where('id', $id)->update(['finish' => true]);

        return response()->json(['message' => "finish this travel", 200]);



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = Auth::id();
        $input = Driver::find($id);
        $deiver = Driver::where('id', $id)->update($request->all());

        return response()->json(['data' => $deiver, 'message' => "updated", 200]);
    }

    public function profile()
{
    $id = Auth::id();
    $driver = Driver::find($id);

    if ($driver) {
        $data = [
            'id' => $driver->id,
            'first_name' => $driver->first_name,
            'last_name' => $driver->last_name,
            'image_driver' => $driver->image_driver,
            'date_of_birth' => $driver->date_of_birth,
            'email' => $driver->email,
            'phoneOne' => $driver->phoneOne,
            'phoneTwo' => $driver->phoneTwo,
            'address' => $driver->address,
            'message' => 'Successfully retrieved driver information'
        ];

        if ($driver->image_driver) {
            $data['image_driver_url'] = asset('uploads/' . $driver->image_driver);
        }

        if ($driver->image_agency) {
            $data['image_agency_url'] = asset('uploads/' . $driver->image_agency);
        }

        return response()->json($data, 200);
    }

    return response()->json(['message' => 'Driver not found'], 404);
}

    public function aviableOrNot()
    {
        $id = Auth::id();

        $driver = Driver::find($id);


        if ($driver->status == '1') {
            Driver::where('id', $id)->update(['status' => false]);

            return response()->json(['message' => "You Are Not Aviable", 200]);
        } else {

            Driver::where('id', $id)->update(['status' => true]);
            return response()->json(['message' => "You Are Aviable", 200]);

        }

    }

}