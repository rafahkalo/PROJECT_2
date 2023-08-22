<?php

namespace App\Http\Controllers;

use App\Models\Offire;
use App\Models\Office;
use App\Models\Type_travel;
use Illuminate\Http\Request;
use App\Repositories\FavoriteRepository;

class OffireController extends Controller
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {

        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function get_Offires($type_travel)
    {
        $type = Type_travel::where('name', $type_travel)->first();


        if (!$type) {
            return response()->json(['message' => 'Invalid type_travel parameter'], 400);
        }

        $userId = auth()->id();
        $favoriteStarIds = $this->favoriteRepository->getFavoriteStarIdsByUser($userId);

        $favoriteOffices = Office::join('offer', 'offer.office_id', '=', 'offices.id')
            ->join('branches', 'offices.branch_id', '=', 'branches.id')
            ->where('offices.star_id', $favoriteStarIds)
            ->where('offices.type_id', $type->id)
            ->select([
                'offices.id',
                'offices.name as Name_Office',
                'branches.name as Branch_Name',
                'offices.location',
                'offer.office_id as office_id',
                'offer.description'
            ])
            ->get();

        if ($favoriteOffices->isEmpty()) {
            return response()->json(['message' => 'not found offires '], 404);
        }
        return response()->json([
            'Offires' =>
            [
                $favoriteOffices
            ]

        ], 200);

    }

}