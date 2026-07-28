<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // GET /api/reviews?mobile_id=X
    public function index(Request $request)
    {
        $mobileId = (int) $request->query('mobile_id', 0);

        if (!$mobileId) {
            return response()->json(['error' => 'mobile_id required'], 400);
        }

        $reviews = Review::where('mobile_id', $mobileId)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = Review::where('mobile_id', $mobileId)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
            ->first();

        return response()->json([
            'success'    => true,
            'data'       => $reviews,
            'avg_rating' => round((float) $stats->avg_rating, 1),
            'total'      => (int) $stats->total,
        ]);
    }

    // POST /api/reviews
    public function store(Request $request)
    {
        $mobileId    = (int) $request->input('mobile_id', 0);
        $reviewerName = trim($request->input('reviewer_name', ''));
        $rating      = (int) $request->input('rating', 0);
        $comment     = trim($request->input('comment', ''));

        if (!$mobileId || !$reviewerName || $rating < 1 || $rating > 5) {
            return response()->json([
                'error' => 'mobile_id, reviewer_name, and rating (1-5) are required',
            ], 400);
        }

        $review = Review::create([
            'mobile_id'     => $mobileId,
            'reviewer_name' => $reviewerName,
            'rating'        => $rating,
            'comment'       => $comment,
        ]);

        return response()->json([
            'success'   => true,
            'review_id' => $review->id,
        ], 201);
    }
}
