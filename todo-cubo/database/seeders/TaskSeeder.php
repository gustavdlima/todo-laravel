<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Task;
use App\Infrastructure\Models\User;
use App\Enums\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'active' => true,
            ]
        );

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

        Task::factory()->forUser($admin)->create([
            'title' => 'Send data to the mayor',
            'description' => 'Data for presentation on neighborhoods without paved streets.',
            'status' => TaskStatus::PENDING,
            'due_date' => now()->addDays(5),
            'completed' => false,
        ]);

        Task::factory()->forUser($admin)->create([
            'title' => 'Meeting',
            'description' => 'Talk about new projects',
            'status' => TaskStatus::IN_PROGRESS,
            'due_date' => now()->addDay(),
            'completed' => false,
        ]);

        Task::factory()->forUser($admin)->create([
            'title' => 'Project delivery',
            'description' => 'Finish and publish version 1.0 of the product',
            'status' => TaskStatus::COMPLETED,
            'due_date' => now()->subDays(2),
            'completed' => true,
        ]);
    }
}
