<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class WatchHistoryController extends Controller
{
    public function update(Request $request, string $videoId): JsonResponse
    {
        $validated = $request->validate([
            'progress_seconds' => 'required|integer',
        ]);

        $history = WatchHistory::updateOrCreate(
            ['user_id' => 1, 'video_id' => $videoId],
            ['progress_seconds' => $validated['progress_seconds']]
        );

        Cache::forget('continue_watching:1');

        return response()->json($history, 200);
    }

    public function continueWatching(): JsonResponse
    {
        $items = Cache::remember('continue_watching:1', 60, function () {
            return WatchHistory::where('user_id', 1)
                ->where('progress_seconds', '>', 0)
                ->orderByDesc('updated_at')
                ->get();
        });

        return response()->json($items, 200);
    }
}
