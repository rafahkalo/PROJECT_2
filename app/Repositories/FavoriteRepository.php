<?php

namespace App\Repositories;

use App\Models\Favorite;

class FavoriteRepository
{
    public function create($data)
    {
        return Favorite::create($data);
    }

    public function findByUserAndStar($userId, $starId)
    {
        return Favorite::where('user_id', $userId)
            ->where('star_id', $starId)
            ->first();
    }

    public function delete($favorite)
    {
        return $favorite->delete();
    }

    public function getFavoriteStarIdsByUser($userId)
    {
        return Favorite::where('user_id', $userId)
            ->pluck('star_id');
    }
}
