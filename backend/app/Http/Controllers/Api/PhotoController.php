<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PhotoController extends Controller
{
    public function index(): JsonResponse
    {
        $photos = Cache::remember('photos.index', 60, function () {
            return Photo::all()->map(fn ($photo) => $this->formatPhoto($photo));
        });

        return response()->json(['data' => $photos], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'caption' => 'nullable|string',
            'album_number' => 'required|string',
            'image' => 'required|image|max:5120',
        ]);

        $path = $request->file('image')->store('photos', 'public');

        $photo = Photo::create([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'image_path' => $path,
            'album_number' => $validated['album_number'],
            'processing_status' => 'processed',
        ]);

        Cache::forget('photos.index');

        return response()->json($this->formatPhoto($photo), 201);
    }

    public function show(string $id): JsonResponse
    {
        $photo = Cache::remember("photos.show.{$id}", 60, function () use ($id) {
            return Photo::findOrFail($id);
        });

        return response()->json(['data' => $this->formatPhoto($photo)], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $photo = Photo::findOrFail($id);
        $photo->delete();

        Cache::forget('photos.index');
        Cache::forget("photos.show.{$id}");

        return response()->json(null, 204);
    }

    private function formatPhoto(Photo $photo): array
    {
        return [
            'id' => $photo->id,
            'title' => $photo->title,
            'caption' => $photo->caption,
            'image_url' => asset('storage/' . $photo->image_path),
            'processing_status' => $photo->processing_status,
            'created_at' => $photo->created_at,
            'updated_at' => $photo->updated_at,
        ];
    }
}
