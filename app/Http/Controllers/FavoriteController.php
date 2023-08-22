<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Star;
use Illuminate\Http\Request;
use App\Repositories\FavoriteRepository;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {

        $this->favoriteRepository = $favoriteRepository;
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        
        $user = Auth::user();


        $favorite = $user->favorite->star;
        return response()->json([
            'user_id' => $user->id,
            'favorite' => $favorite
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'number' => 'required',
        ]);
        $star = Star::where('number', $validatedData)->first();


        if (!$star) {
            return response()->json(['message' => 'No offices found for the given number of stars'], 404);
        }
        $user = Auth::user();


        $isUpdate = $user->favorite->update([
            'star_id' => $star->id,
        ]);

        if ($isUpdate) {

            return response()->json([
                'message' => 'Favorite updated successfully',
                'data' => [
                    'user_id' => $user->id,
                    'favorite' => $user->favorite->star,
                ]

            ], 200);
        } else
            return response()->json([
                'message' => 'Favorite updated not successfully',
            ]);

    }

}