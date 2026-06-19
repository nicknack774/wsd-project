<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'title' => 'Setup project repository',
            'description' => 'Initialize Git and project structure',
            'status' => 'done',
            'album_number' => '77963',
        ]);

        Task::create([
            'title' => 'Implement CRUD API',
            'description' => 'Build Laravel REST API for tasks',
            'status' => 'done',
            'album_number' => '77963',
        ]);

        Task::create([
            'title' => 'Add Redis caching',
            'description' => 'Implement cache-aside pattern for tasks.index',
            'status' => 'in_progress',
            'album_number' => '77963',
        ]);

        Task::create([
            'title' => 'Dockerize the application',
            'description' => 'Create Dockerfiles and Compose setup',
            'status' => 'todo',
            'album_number' => '77963',
        ]);
    }
}
