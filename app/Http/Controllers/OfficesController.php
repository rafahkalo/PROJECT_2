<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Car;
use App\Models\Star;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use App\Models\Number;
use App\Http\Controllers\FileController as FileController;
use App\Models\Communal;
use App\Models\Wallet_Office;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\wallet_admin;
use App\Models\Offire;
use App\Models\Goverments;
use App\Models\Type_travel;
use App\Repositories\FavoriteRepository;

class OfficesController extends FileController
{
    ////Tasneem////
    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }
    public function ShowAllofficefatma()
    {

        $data = Office::where('status', 'true')->get();

        return response()->json(['AllDriver' => $data], 200);




    }

    public function show_offices_favorite($type_travel)
    {
        $type = Type_travel::where('name', $type_travel)->first();


        if (!$type) {
            return response()->json(['message' => 'Invalid type_travel parameter'], 400);
        }

        $userId = auth()->id();
        $favoriteStarIds = $this->favoriteRepository->getFavoriteStarIdsByUser($userId);


        $favoriteOffices = Office::where('star_id', $favoriteStarIds)
            ->where('type_id', $type->id)
            ->get();

        if ($favoriteOffices->isEmpty()) {
            return response()->json(['message' => 'not found offices '], 404);
        }


        $favoriteOfficesData = $favoriteOffices->map(function ($office) {

            return [
                'id' => $office->id,
                'name' => $office->name,
                'branch' => $office->branch->name,
                'stars' => $office->stars->number,
                'location' => $office->location,
                'phoneOne' => $office->phoneOne,
                'phoneTwo' => $office->phoneTwo,
                'email' => $office->email,
            ];
        });
        return response()->json(['favorite_offices' => $favoriteOfficesData], 200);
    }
    //////////

    public function UpdateAmountCommuncal(Request $request, $id)
    {


        $input = Communal::find($id);

        if (isset($input)) {

            $input->amount_communal = $request->amount_communal;



            $input->update();


            return response()->json(['message' => 'amount_communal update successfully'], 200);
        }

        return response()->json(['message' => 'Error'], 401);
    }


    public function showdetailsOffice($id)
    {
        $office = Office::find($id);
    
        if ($office) {
            $branch = $office->branch;
            $type = $office->type_id;
            $star = $office->star_id;
            $c = $branch->gov_id;
            $government = Goverments::where('id', $c)->first();
            $typec = Type_travel::where('id', $type)->first();
            $stars = Star::where('id', $star)->first();
            $response1 = Office::where('id', $id)
                ->with([
                    'car' => function ($query) {
                        $query->with('driver');
                    },
                    'driver'
                ])
                ->get();
            
            $response = [
                'government' => $government->name,
                'type_Travel' => $typec->name,
                'star' => $stars->name,
                'stars' => $stars->number,
                'office' => $response1,
            ];
            
            // Add image and contract URLs if they exist
            if ($office->image) {
                $response['image_url'] = asset('uploads/' . $office->image);
            }
            
            if ($office->contract) {
                $response['contract_url'] = asset('uploads/' . $office->contract);
            }
    
            return response()->json($response, 200);
        } else {
            return response()->json(['message' => 'Office not found'], 404);
        }
    }
    




    public function requestJoin(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'branch_id' => 'required',
            'type_id' => 'required',
            'star_id' => 'required',
            'location' => 'required|string',
            'image' => 'required',
            'discreption' => 'required|string',
            'email' => 'required',
            'password' => 'required',
            'phoneOne' => 'required|string|regex:/^09[0-9]{8}$/',
            'phoneTwo' => 'required|string|regex:/^09[0-9]{8}$/',
            'contract' => 'required',
            'code' => 'required',
            'amount' => 'required',
        ]);
        if (!$data) {
            return response()->json([
                'message' =>
                ' errort'
            ], 200);

        }

        $communal = Communal::first();
        $mony = $request->amount;

        if ($mony < $communal->amount_communal) {

            return response()->json([
                'message' =>
                'The amount paid is insufficient.
    Try again by paying the correct amount'
            ], 200);

        }
        if ($request->amount == $communal->amount_communal) {


            $x = 0;




            $input = Office::where('email', $request->email)->first();

            if ($input) {
                return response()->json(['message' => 'There is a similar email, please use a new email'], 500);
            }

            $photo = $this->saveFile($request, 'image', public_path('/uploads'));

            $photoo = $this->saveFile($request, 'contract', public_path('/uploads'));

            $office = Office::create([
                'image' => $photo,
                'name' => $request->name,
                'branch_id' => $request->branch_id,
                'type_id' => $request->type_id,
                'contract' => $photoo,
                'phoneOne' => $request->phoneOne,
                'phoneTwo' => $request->phoneTwo,
                'star_id' => $request->star_id,
                'location' => $request->location,
                'discreption' => $request->discreption,

                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            Wallet_Office::create([
                'code' => $request->code,
                'amount' => $x,
                'office_id' => $office->id
            ]);
            $in = wallet_admin::first();

            $wallet_admin = wallet_admin::first();
            // Update the existing record with the new amount
            if ($wallet_admin->code == 1222)
                $wallet_admin->update([
                    'amount' => $in->amount + $request->amount,

                ]);

            return response()->json(['message' => 'Office saved successfully'], 200);

        }





        if ($mony > $communal->amount_communal) {



            $input = Office::where('email', $request->email)->first();

            if ($input) {
                return response()->json(['message' => 'There is a similar email, please use a new email'], 500);
            }

            $photo = $this->saveFile($request, 'image', public_path('/uploads'));

            $photoo = $this->saveFile($request, 'contract', public_path('/uploads'));

            $office = Office::create([
                'image' => $photo,
                'name' => $request->name,
                'branch_id' => $request->branch_id,
                'type_id' => $request->type_id,
                'contract' => $photoo,
                'phoneOne' => $request->phoneOne,
                'phoneTwo' => $request->phoneTwo,
                'star_id' => $request->star_id,
                'location' => $request->location,
                'email' => $request->email,
                'discreption' => $request->discreption,

                'password' => Hash::make($request->password)
            ]);






            Wallet_Office::create([
                'code' => $request->code,
                'amount' => $request->amount - $communal->amount_communal,
                'office_id' => $office->id
            ]);






            $in = wallet_admin::first();

            $wallet_admin = wallet_admin::first();
            // Update the existing record with the new amount
            if ($wallet_admin->code == 1222)
                $wallet_admin->update([
                    'amount' => $in->amount + $communal->amount_communal,

                ]);

            return response()->json(['message' => 'Office saved successfully'], 200);
        }
    }


    public function showAllOfficesnotAccept()
    {
        $offices = Office::where('status', '0')->get();
        return response()->json(['AllOffices' => $offices], 200);
    }

    public function AcceptOffice($id)
    {

        $Admin = Auth::id();

        $Office = Office::find($id)->update([
            'status' => 'true'




        ]);


        $office = Office::find($id);

        $current_time = time();
        $created_time = strtotime($office->created_at);
        $hours_difference = round(($current_time - $created_time) / 3600);
        if ($hours_difference >= (24 * 365)) {
            $office->delete();
            return response()->json(['message' => 'Office deleted'], 200);
        }
        return response()->json([
            'message' => 'Accept this Office',
            'info office' => $Office
        ], 200);

    }



    public function RefuseOffice($id)
    {

        Office::find($id)->delete();
        return response()->json([
            'message' => 'Cancel this Office',
        ], 200);
    }

    public function searchByName(Request $request)
    {

        $officeS = Office::where('name', $request->name)->get();

        $Data = $officeS->map(function ($office) {

            return [
                'id' => $office->id,
                'name' => $office->name,
                'branch' => $office->branch->name,
                'stars' => $office->stars->number,
                'location' => $office->location,
                'govermant' => $office->branch->govermant,
                'Type' => $office->Type_travel->name,


            ];
        });
        

        return response()->json([$Data], 200);

    }
    public function getInformationOffice($id)
    {
        $office = Office::find($id);

        if (!$office) {
            return response()->json(['message' => 'Office not found'], 404);
        }

        if (!$office->status) {
            return response()->json(['message' => 'Office is not approved'], 403);
        }


        return response()->json($office);
    }

    public function getOfficesByStars($stars)
    {

        $star = Star::where('number', $stars)->first();

        if (!$star) {
            return response()->json(['message' => 'No offices found for the given number of stars'], 404);
        }

        $offices = Office::where('star_id', $star->id)->where('status', 'true')->get();

        return response()->json($offices);


    }


    public function editStar(Request $request, $id)
    {
        $star = Star::where('number', $request->input('stars'))->first();
        if (!$star) {
            return response()->json(['message' => 'Invalid star rating'], 404);
        }
        $Office = Office::find($id)->update([
            'star_id' => $star->id,
        ]);

        return response()->json(['message' => 'Stars updated successfully'], 200);
    }

    public function AddOffice(Request $request)
    {

        $data = $request->validate([

            'name' => 'required|string',
            'branch_id' => 'required',
            'type_id' => 'required',
            'star_id' => 'required',
            'location' => 'required|string',
            'image' => 'required',
            'contract' => 'required',
            'email' => 'required',
            'status' => 'required',
            'password' => 'required',
            'phoneOne' => 'required|string|regex:/^09[0-9]{8}$/',
            'phoneTwo' => 'required|string|regex:/^09[0-9]{8}$/',
            'discreption' => 'required',

        ]);




        $input = Office::where('email', $request->email)->first();

        if ($input) {
            return response()->json(['message' => 'There is a similar email'], 500);
        } else {

            $photo = $this->saveFile($request, 'image', public_path('/uploads'));

            $photoo = $this->saveFile($request, 'contract', public_path('/uploads'));

            $office = Office::create([
                'image' => $photo,
                'name' => $request->name,
                'branch_id' => $request->branch_id,
                'type_id' => $request->type_id,
                'contract' => $photoo,
                'phoneOne' => $request->phoneOne,
                'phoneTwo' => $request->phoneTwo,
                'star_id' => $request->star_id,
                'location' => $request->location,
                'email' => $request->email,
                'status' => $request->status,
                'discreption' => $request->discreption,
                'password' => Hash::make($request->password)
            ]);


            $current_time = time();
            $created_time = strtotime($office->created_at);
            $hours_difference = round(($current_time - $created_time) / 3600);
            if ($hours_difference >= (24 * 365)) {
                $office->delete();
                return response()->json(['message' => 'Office deleted'], 200);
            }

            return response()->json(['message' => 'Office saved successfully'], 200);

        }
    }








    public function loginOffice(Request $request)
    {


        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $office = Office::where('email', $fields['email'])->first();

        if ($office->status == 'true') {



            // Check password
            if (!$office || !Hash::check($fields['password'], $office->password)) {
                return response([
                    'message' => 'Password is worng'
                ], 401);
            }
            $token = $office->createToken('ooficeToken')->plainTextToken;

            $response = [
                'message' => 'Welcome With You',
                'token' => $token
            ];

            return response($response, 201);
        } else {
            $response = [
                'message' => 'Wait the Acceptance',

            ];
            return response($response, 201);
        }
    }


    public function ShowAlloffice()
    {

        $data = Office::query()->select(
            'id',
            'phoneTwo',
            'phoneOne',
            'contract',
            'image',
            'location',
            'name',
            'type_id',
            'star_id',
            'branch_id'
        )->where('status', 'true')->get();
        $data['message'] = 'successfull';
        return response()->json($data, 200);


    }


    public function ShowAllOffer()
    {
        $id = Auth::id();
        $data = Office::find($id)->offir()->get();
        return response()->json($data, 200);


    }


    public function getIdOffer($id)
    {

        $offer = Offire::where('id', $id)->get();
        return response()->json($offer, 200);
    }


    public function RefuseOffire($id)
    {

        Offire::find($id)->delete();
        return response()->json([
            'message' => 'Cancel this Offire',
        ], 200);
    }

    public function AddOffer(Request $request)
    {



        $office_id = Auth::id();
        $rules = [

            'description' => 'required|string',


        ];
        $Offire = Offire::create([

            'description' => $request->description,
            'office_id' => $office_id,

        ]);

        return response()->json(['message' => 'Offer saved successfully'], 200);

    }

    public function updateOffice(Request $request)
    {
        $id = Auth::id();
        $input = Office::find($id);
        $office = Office::where('id', $id)->update($request->all());

        return response()->json(['data' => $office, 'message' => "updated", 200]);
    }


}