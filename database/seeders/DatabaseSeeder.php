<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Task;

/**
 * class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        // Create two parent tasks
        DB::table('tasks')->insert([
            'user_id' => User::all()->random()->id,
            'status' => fake()->randomElement(['todo', 'done']),
            'priority' => fake()->numberBetween(0, 5),
            'title' => fake()->title(),
            'description' => fake()->realText(),
            'completed_at' => fake()->dateTimeThisYear('+1 days')
        ]);
        DB::table('tasks')->insert([
            'user_id' => User::all()->random()->id,
            'status' => fake()->randomElement(['todo', 'done']),
            'priority' => fake()->numberBetween(0, 5),
            'title' => fake()->title(),
            'description' => fake()->realText(),
            'completed_at' => fake()->dateTimeThisYear('+1 days')
        ]);

        Task::factory(20)->create();
    }
}
