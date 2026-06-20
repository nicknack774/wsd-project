<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->query('page', 1);
        $cacheKey = "videos.popular.page.{$page}";

        $videos = Cache::remember($cacheKey, 60, function () {
            return Video::all();
        });

        return response()->json($videos, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'genre' => 'required|string',
            'duration_minutes' => 'required|integer',
            'video_url' => 'required|url',
            'album_number' => 'required|string',
        ]);

        $video = Video::create($validated);

        return response()->json($video, 201);
    }

    public function show(string $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        return response()->json($video, 200);
    }
}
