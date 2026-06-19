<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Cache::remember('tasks.index', 60, function () {
            return Task::all();
        });

        return response()->json($tasks, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'album_number' => 'required|string',
        ]);

        $task = Task::create($validated);

        Cache::forget('tasks.index');

        return response()->json($task, 201);
    }

    public function show(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        return response()->json($task, 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'album_number' => 'sometimes|required|string',
        ]);

        $task->update($validated);

        Cache::forget('tasks.index');

        return response()->json($task, 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        Cache::forget('tasks.index');

        return response()->json(null, 204);
    }
}
