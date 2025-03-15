<?php

namespace Database\Factories;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(TaskStatus::cases()),
            'completed' => $this->faker->boolean(20), // 20%
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
        ];
    }

     /**
     * Indicates that the task is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TaskStatus::PENDING,
                'completed' => false,
            ];
        });
    }

    /**
     * Indicates that the task is progres.s
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TaskStatus::IN_PROGRESS,
                'completed' => false,
            ];
        });
    }

    /**
     * Indicates that the task is completed
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TaskStatus::COMPLETED,
                'completed' => true,
            ];
        });
    }

}
