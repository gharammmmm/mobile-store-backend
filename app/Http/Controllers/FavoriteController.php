<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // GET /api/favorites
    public function index(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthenticated'
            ], 401);
        }

        $favorites = Favorite::where('user_id', $userId)
            ->join('mobiles', 'favorites.mobile_id', '=', 'mobiles.id')
            ->select([
                'favorites.id',
                'favorites.mobile_id',
                'mobiles.name',
                'mobiles.brand',
                'mobiles.price',
                'mobiles.image_url',
                'mobiles.ram',
                'mobiles.storage',
            ])
            ->orderBy('favorites.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    // POST /api/favorites/toggle
    public function toggle(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthenticated'
            ], 401);
        }

        $mobileId = (int) $request->input('mobile_id');

        if (!$mobileId) {
            return response()->json([
                'success' => false,
                'error' => 'mobile_id required'
            ], 400);
        }

        $exists = Favorite::where('user_id', $userId)
            ->where('mobile_id', $mobileId)
            ->first();

        if ($exists) {
            $exists->delete();
            $action = 'removed';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'mobile_id' => $mobileId,
            ]);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action
        ]);
    }
}
