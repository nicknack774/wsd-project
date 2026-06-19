<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::all();

        return response()->json($restaurants, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'album_number' => 'required|string',
        ]);

        $restaurant = Restaurant::create($validated);

        return response()->json($restaurant, 201);
    }

    public function show(string $id): JsonResponse
    {
        $restaurant = Restaurant::findOrFail($id);

        return response()->json($restaurant, 200);
    }

    public function nearby(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'required|numeric',
        ]);

        $lat = $validated['lat'];
        $lng = $validated['lng'];
        $radius = $validated['radius'];

        $cacheKey = "nearby:{$lat}:{$lng}:{$radius}";

        $results = Cache::remember($cacheKey, 60, function () use ($lat, $lng, $radius) {
            $restaurants = Restaurant::all();

            $nearby = $restaurants->map(function ($restaurant) use ($lat, $lng) {
                $distance = $this->haversineDistance(
                    $lat,
                    $lng,
                    (float) $restaurant->latitude,
                    (float) $restaurant->longitude
                );

                $restaurant->distance_km = round($distance, 2);

                return $restaurant;
            })->filter(function ($restaurant) use ($radius) {
                return $restaurant->distance_km <= $radius;
            })->sortBy('distance_km')->values();

            return $nearby;
        });

        return response()->json($results, 200);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}
