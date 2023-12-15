<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalAccessToken>
 */
class PersonalAccessTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = PersonalAccessToken::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            $user->tokens()->create([
                'name' => 'access-token',
                'token' => Str::uuid(),
                'refresh_token' => Str::uuid()
            ])
        ];
    }
}
