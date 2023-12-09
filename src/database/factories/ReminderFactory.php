<?php

namespace Database\Factories;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    protected $model = Reminder::class;
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence,
            'user_id' => $user->id,
            'description' => $this->faker->paragraph,
            'remind_at' => $this->faker->dateTimeBetween('now', '+1 week')->getTimestamp(),
            'event_at' => $this->faker->dateTimeBetween('+1 week', '+2 weeks')->getTimestamp(),
        ];
    }
}