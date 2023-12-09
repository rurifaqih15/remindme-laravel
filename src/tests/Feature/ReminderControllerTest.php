<?php

use App\Models\User;
use App\Models\Reminder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReminderControllerTest extends TestCase
{
   public function getAuth(){
        $response = $this->postJson('/api/session', [
            'email' => 'bob@mail.com',
            'password' => 123456,
        ]);
        return json_decode($response->getContent())->data;
    }

    public function testListReminders()
    {
        Reminder::factory()->create();
        $response = $this->getJson('/api/reminders?limit=5', [
            'Authorization' => 'Bearer ' . $this->getAuth()->access_token,
            ]);
       

        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'data' => [
                    'limit' => 5,
                ],
            ])
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'reminders' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'remind_at',
                            'event_at',
                        ],
                    ],
                    'limit',
                ],
            ]);
    }

    public function testCreateReminder()
    {
        $headers = [
            'Authorization' => 'Bearer '. $this->getAuth()->access_token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $data = [
            'title' => 'Meeting with Bob',
            'description' => 'Discuss about the new project related to the new system',
            'remind_at' => 1701246722,
            'event_at' => 1701223200,
        ];

        $response = $this->postJson('/api/reminders', $data, $headers);
       $id =  json_decode($response->getContent())->data->id;
        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'data' => [
                    'id' => $id,
                    'title' => 'Meeting with Bob',
                    'description' => 'Discuss about the new project related to the new system',
                    'remind_at' => 1701246722,
                    'event_at' => 1701223200,
                ],
            ]);
    }

    public function testViewReminder()
    {
        $headers = [
            'Authorization' => 'Bearer '. $this->getAuth()->access_token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $reminder = Reminder::factory()->create();

        $response = $this->get("/api/reminders/{$reminder->id}",$headers);
        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'data' => [
                    'id' => $reminder->id,
                    'title' => $reminder->title,
                    'description' => $reminder->description,
                    'remind_at' => $reminder->remind_at,
                    'event_at' =>$reminder->event_at,
                ],
            ]);
    }

    public function testEditReminder()
    {
        $headers = [
            'Authorization' => 'Bearer '. $this->getAuth()->access_token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];


        $reminder = Reminder::factory()->create();

        $response = $this->putJson("/api/reminders/{$reminder->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'remind_at' => 1701246722,
            'event_at' => 1701223200,
        ], $headers);
        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'data' => [
                    'id' => $reminder->id,
                    'title' => 'Updated Title',
                    'description' => 'Updated Description',
                    'remind_at' => 1701246722,
                    'event_at' => 1701223200,
                ],
            ]);
    }

    public function testDestroyReminder()
    {
        $headers = [
            'Authorization' => 'Bearer '. $this->getAuth()->access_token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];


        $reminder = Reminder::factory()->create();

        $response = $this->delete("/api/reminders/{$reminder->id}",[], $headers);
        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
            ]);
    }
}
