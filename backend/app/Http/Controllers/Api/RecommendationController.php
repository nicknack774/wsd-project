<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\WatchHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class RecommendationController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = 1;
        $cacheKey = "recommendations:{$userId}";

        $recommendations = Cache::remember($cacheKey, 60, function () use ($userId) {
            $watchedGenres = WatchHistory::where('user_id', $userId)
                ->join('videos', 'watch_histories.video_id', '=', 'videos.id')
                ->pluck('videos.genre')
                ->unique();

            if ($watchedGenres->isEmpty()) {
                return Video::limit(5)->get();
            }

            return Video::whereIn('genre', $watchedGenres)
                ->whereNotIn('id', WatchHistory::where('user_id', $userId)->pluck('video_id'))
                ->limit(5)
                ->get();
        });

        return response()->json($recommendations, 200);
    }
}
