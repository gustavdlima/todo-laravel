<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Task;
use App\Infrastructure\Models\TaskComment;
use App\Infrastructure\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $users = User::factory(3)->create();
        }

        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            $tasks = Task::factory(10)->create([
                'user_id' => $users->random()->id
            ]);
        }

        foreach ($tasks as $task) {
            $commentCount = rand(0, 5);

            TaskComment::factory($commentCount)->create([
                'task_id' => $task->id,
                'user_id' => $users->random()->id
            ]);
        }
    }
}
