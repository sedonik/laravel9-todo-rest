<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Task;

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
    public function definition(): array
    {
        return [
            'parent_task_id' => Task::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'status' => fake()->randomElement(['todo', 'done']),
            'priority' => fake()->numberBetween(0, 5),
            'title' => fake()->title(),
            'description' => fake()->realText(),
            'completed_at' => fake()->dateTimeThisYear('+1 days')
        ];
    }
}
