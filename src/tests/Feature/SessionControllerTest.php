<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PersonalAccessToken;

class SessionControllerTest extends TestCase
{
    public function test_create_session_endpoint()
    {
        $response = $this->postJson('/api/session', [
            'email' => 'bob@mail.com',
            'password' => 123456,
        ]);
        $user = User::where('email','bob@mail.com')->first();
        $response->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'user' => [
                        'id',
                        'email',
                        'name',
                    ],
                    'access_token',
                    'refresh_token',
                ],
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => get_class($user),
        ]);
    }

    public function test_update_token_endpoint()
    {
        $response = $this->postJson('/api/session', [
            'email' => 'bob@mail.com',
            'password' => 123456,
        ]);
        $data = json_decode($response->getContent())->data;

        $response = $this->putJson('/api/session', [], [
            'Authorization' => 'Bearer ' . $data->refresh_token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'access_token',
                ],
            ]);
    }
}




