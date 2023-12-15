<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\ReminderController;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class ReminderControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsRemindersForAuthenticatedUser()
    {
        $this->runSeed();
        $user = User::find(1);
        $this->actingAs($user);

        Reminder::factory(5)->create(['user_id' => $user->id]);

        $controller = new ReminderController();
        $token = $this->createAccessToken($user);
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $response = $controller->index($request);
        $checkData = get_object_vars($response->getData());
        $checkReminder =  get_object_vars($checkData['data']->reminders[0]);
        $this->assertEquals(true, $checkData['ok']);
        $this->assertArrayHasKey('id',$checkReminder);
        $this->assertArrayHasKey('title',$checkReminder);
        $this->assertArrayHasKey('description',$checkReminder);
        $this->assertArrayHasKey('remind_at',$checkReminder);
        $this->assertArrayHasKey('event_at',$checkReminder);
        $this->assertEquals(10, $checkData['data']->limit);
    }

    public function testStoreCreatesNewReminder()
    {
        $this->runSeed();
        $user = User::first();
        $this->actingAs($user);

        $remindAt = now()->addDay()->getTimestamp();
        $eventAt = now()->addWeek()->getTimestamp();
        $request = new StoreReminderRequest([
            'title' => 'Test Reminder',
            'description' => 'This is a test reminder',
            'remind_at' => $remindAt,
            'event_at' => $eventAt,
        ]);
        $token = $this->createAccessToken($user);
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $controller = new ReminderController();
        $response = $controller->store($request);
      
        $checkData = get_object_vars($response->getData());
        
        $this->assertEquals(true, $checkData['ok']);
        $this->assertArrayHasKey('data', $checkData);
        $this->assertEquals('Test Reminder', $checkData['data']->title);
        $this->assertEquals('This is a test reminder', $checkData['data']->description);
        $this->assertEquals($remindAt, $checkData['data']->remind_at);
        $this->assertEquals($eventAt, $checkData['data']->event_at);
        $this->assertDatabaseHas('reminders', [
            'user_id' => $user->id,
            'title' => 'Test Reminder',
        ]);
    }

    public function testShowReturnsSingleReminder()
    {
        $this->runSeed();
        $user = User::first();
        $this->actingAs($user);

        $reminder = Reminder::factory()->create(['user_id' => $user->id]);

        $controller = new ReminderController();
        $response = $controller->show($reminder->id);
        $checkData = get_object_vars($response->getData());
        $this->assertEquals(true, $checkData['ok']);
        $this->assertArrayHasKey('data', $checkData);
        $this->assertEquals($reminder->title, $checkData['data']->title);
        $this->assertEquals($reminder->description, $checkData['data']->description);
        $this->assertEquals($reminder->remind_at, $checkData['data']->remind_at);
        $this->assertEquals($reminder->event_at, $checkData['data']->event_at);
    }

    public function testUpdateUpdatesReminder()
    {
        $this->runSeed();
        $user = User::first();
        $this->actingAs($user);

        $reminder = Reminder::factory()->create(['user_id' => $user->id]);

        $request = new UpdateReminderRequest([
            'title' => 'Updated Reminder',
            'description' => 'Updated description',
        ]);

        $controller = new ReminderController();
        $response = $controller->update($request, $reminder->id);
        $checkData = get_object_vars($response->getData());
        
        $this->assertEquals(true, $checkData['ok']);
        $this->assertArrayHasKey('data', $checkData);
        $this->assertEquals('Updated Reminder', $checkData['data']->title);
        $this->assertEquals('Updated description', $checkData['data']->description);
        $this->assertEquals($reminder->remind_at, $checkData['data']->remind_at);
        $this->assertEquals($reminder->event_at, $checkData['data']->event_at);

        $this->assertDatabaseHas('reminders', [
            'id' => $reminder->id,
            'title' => 'Updated Reminder',
            'description' => 'Updated description',
        ]);
    }

    public function testDestroyDeletesReminder()
    {
        $this->runSeed();
        $user = User::first();
        $this->actingAs($user);

        $reminder = Reminder::factory()->create(['user_id' => $user->id]);

        $controller = new ReminderController();
        $response = $controller->destroy($reminder->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseMissing('reminders', ['id' => $reminder->id]);
    }
}
