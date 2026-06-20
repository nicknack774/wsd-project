<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class WatchlistController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Watchlist::where('user_id', 1)->get();

        return response()->json($items, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'video_id' => 'required|integer',
        ]);

        $item = Watchlist::firstOrCreate([
            'user_id' => 1,
            'video_id' => $validated['video_id'],
        ]);

        Cache::forget('continue_watching:1');

        return response()->json($item, 201);
    }

    public function destroy(string $videoId): JsonResponse
    {
        Watchlist::where('user_id', 1)->where('video_id', $videoId)->delete();

        return response()->json(null, 204);
    }
}
