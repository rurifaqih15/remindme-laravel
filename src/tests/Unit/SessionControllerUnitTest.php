<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use App\Http\Requests\SessionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SessionControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSessionWithValidCredentials()
    {
       $this->runSeed();
       

        $user = User::inRandomOrder()->first();
        $this->actingAs($user);

        $request = new SessionRequest([
            'email' => $user->email,
            'password' => 123456,
        ]);

        $controller = new SessionController();
        $response = $controller->createSession($request);
        $dataCheck = get_object_vars($response->getData());
        $personalToken = PersonalAccessToken::where('tokenable_id',$user->id)->orderBy('id','desc')->first();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($dataCheck['ok']);
        $this->assertArrayHasKey('data',($dataCheck));
        $this->assertArrayHasKey('user',get_object_vars($dataCheck['data']));
        $this->assertEquals($user->only('id','name','email'),get_object_vars($dataCheck['data']->user));
        $this->assertEquals($personalToken->token, $dataCheck['data']->access_token);
        $this->assertEquals($personalToken->refresh_token, $dataCheck['data']->refresh_token);
        
    }

    public function testCreateSessionWithInvalidCredentials()
    {

        $request = new SessionRequest([
            'email' => 'nonexistent@example.com',
            'password' => 'invalid_password',
        ]);
       
        $controller = new SessionController();
        $response = $controller->createSession($request);
        $checkData = get_object_vars($response->getData());
        // Assert the response
        $this->assertEquals(401,$response->getStatusCode());
        $this->assertEquals('ERR_INVALID_CREDS', $checkData['err']);
        $this->assertEquals('incorrect username or password',$checkData['msg']);
    }

    public function testUpdateTokenWithValidToken()
    {
        $this->runSeed();
        $user = User::inRandomOrder()->first();
        $personalAccessToken =  $user->tokens()->create([
            'name' => 'access-token',
            'token' => Str::uuid(),
            'refresh_token' => Str::uuid()
        ]);

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $personalAccessToken->refresh_token);

        $controller = new SessionController();
        $response = $controller->updateToken($request);
        $personalAccessTokenNew = PersonalAccessToken::where(['refresh_token' => $personalAccessToken->refresh_token])->first();
        $checkData = get_object_vars($response->getData());

        $this->assertEquals($checkData['ok'], true);
        $this->assertEquals($checkData['data']->access_token,$personalAccessTokenNew->token);
      
        //check not valid refresh token
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . Str::uuid());
        $response = $controller->updateToken($request);
        $checkData = get_object_vars($response->getData());

        $this->assertEquals(401,$response->getStatusCode());
        $this->assertEquals($checkData['ok'], false);
        $this->assertEquals('ERR_INVALID_REFRESH_TOKEN', $checkData['err']);
        $this->assertEquals('invalid refresh token',$checkData['msg']);
    }

    public function testUpdateTokenWithInvalidToken()
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer invalid_token');

        $controller = new SessionController();
        $response = $controller->updateToken($request);
        $checkData = get_object_vars($response->getData());
        // Assert the response
        $this->assertEquals(401,$response->getStatusCode());
        $this->assertEquals($checkData['ok'], false);
        $this->assertEquals('ERR_INVALID_REFRESH_TOKEN', $checkData['err']);
        $this->assertEquals('invalid refresh token',$checkData['msg']);
    }

}
