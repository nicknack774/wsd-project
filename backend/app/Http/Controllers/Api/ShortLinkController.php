<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use App\Support\Base62;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ShortLinkController extends Controller
{
    public function index(): JsonResponse
    {
        $links = Cache::remember('short_links.index', 60, function () {
            return ShortLink::all();
        });

        return response()->json($links, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'original_url' => 'required|url',
            'album_number' => 'required|string',
        ]);

        $link = ShortLink::create([
            'original_url' => $validated['original_url'],
            'album_number' => $validated['album_number'],
            'short_code' => 'temp',
        ]);

        $link->short_code = Base62::encode($link->id);
        $link->save();

        Cache::forget('short_links.index');

        return response()->json($link, 201);
    }

    public function show(string $id): JsonResponse
    {
        $link = ShortLink::findOrFail($id);

        return response()->json($link, 200);
    }
}
