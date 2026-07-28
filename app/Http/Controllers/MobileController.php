<?php

namespace App\Http\Controllers;

use App\Models\Mobile;
use App\Services\AiPredictionService;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    // GET /api/mobiles
public function index(Request $request)
{
    $query = Mobile::query();

    if ($request->filled('brand')) {
        $query->where('brand', $request->brand);
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', (float) $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', (float) $request->max_price);
    }

    if ($request->filled('ram')) {
        $query->where('ram', '>=', (int) $request->ram);
    }

    if ($request->filled('storage')) {
        $query->where('storage', '>=', (int) $request->storage);
    }

    if ($request->filled('search')) {
        $term = '%' . $request->search . '%';
        $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', $term)
              ->orWhere('brand', 'LIKE', $term);
        });
    }

    $mobiles = $query->orderBy('created_at', 'desc')->get();

    return response()->json([
        'success' => true,
        'data' => $mobiles,
        'count' => $mobiles->count(),
    ]);
}

    // GET /api/mobiles/{id}
    public function show(int $id, AiPredictionService $aiService)
    {
        $mobile = Mobile::find($id);

        if (!$mobile) {
            return response()->json(['error' => 'Mobile not found'], 404);
        }

        $data                  = $mobile->toArray();
        $data['ai_prediction'] = $aiService->predict($data);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }
}
