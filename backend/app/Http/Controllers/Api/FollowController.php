<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class FollowController extends Controller
{
    public function follow(int $id): JsonResponse
    {
        Follow::firstOrCreate([
            'follower_id' => 1,
            'followed_id' => $id,
        ]);

        Cache::forget('feed:1:10');

        return response()->json(['message' => 'Followed successfully'], 200);
    }

    public function unfollow(int $id): JsonResponse
    {
        Follow::where('follower_id', 1)->where('followed_id', $id)->delete();

        Cache::forget('feed:1:10');

        return response()->json(['message' => 'Unfollowed successfully'], 200);
    }
}
