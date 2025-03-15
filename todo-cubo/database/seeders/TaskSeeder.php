<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::factory()
            ->pending()
            ->count(10)
            ->create();

        Task::factory()
            ->inProgress()
            ->count(5)
            ->create();

        Task::factory()
            ->completed()
            ->count(8)
            ->create();

        Task::factory()->create([
            'title' => 'Send data to the mayor',
            'description' => 'Data for presentation on neighborhoods without paved streets.',
            'status' => TaskStatus::PENDING,
            'due_date' => now()->addDays(5),
            'completed' => false,
        ]);

        Task::factory()->create([
            'title' => 'Meeting',
            'description' => 'Talk about new projects',
            'status' => TaskStatus::IN_PROGRESS,
            'due_date' => now()->addDay(),
            'completed' => false,
        ]);

        Task::factory()->create([
            'title' => 'Project delivery',
            'description' => 'Finalize and publish version 1.0 of the product',
            'status' => TaskStatus::COMPLETED,
            'due_date' => now()->subDays(2),
            'completed' => true,
        ]);
    }
}
