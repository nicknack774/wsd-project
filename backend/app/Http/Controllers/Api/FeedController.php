<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', 10);
        $userId = 1;

        $cacheKey = "feed:{$userId}:{$limit}";

        $feed = Cache::remember($cacheKey, 60, function () use ($userId, $limit) {
            $followedIds = Follow::where('follower_id', $userId)->pluck('followed_id');

            return Photo::query()
                ->whereIn('user_id', $followedIds)
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->unique('id')
                ->values();
        });

        return response()->json([
            'count' => $feed->count(),
            'data' => $feed,
        ], 200);
    }
}
