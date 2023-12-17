<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SessionRequest;
use Auth;
use Str;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
class SessionController extends Controller
{
    public function createSession(SessionRequest $request){
    
        $credentials = $request->only('email','password');
        if ( Auth::attempt($credentials)){
            $user = Auth::user();
            $personalAccessToken = $user->tokens()->create([
                'name' => 'access-token',
                'token' => Str::uuid(),
                'refresh_token' => Str::uuid()
            ]);
            $data = [
                'user' => new UserResource($user),
                'access_token' => $personalAccessToken->token,
                'refresh_token' => $personalAccessToken->refresh_token,
            ];
            return successResponse($data,200);
        }
        return errorResponse('ERR_INVALID_CREDS','incorrect username or password',401);
    }

public function updateToken(Request $request){
        $authorizationHeader = $request->header('Authorization');
        if (!isValidAuthorizationHeader($authorizationHeader)) {
            throw new HttpException(401,'Invalid access token');
        }
        $refreshToken = str_replace('Bearer ', '', $authorizationHeader);
        $personalAccessToken = PersonalAccessToken::where('refresh_token',$refreshToken)->first();
        if(!$personalAccessToken){
            return errorResponse('ERR_INVALID_REFRESH_TOKEN','invalid refresh token',401);
        }
        PersonalAccessToken::where('id', $personalAccessToken->id)
        ->update([
            'token' => Str::uuid(),
            'expires_at' => Carbon::now()->addSeconds(20)
        ]);
        
        $personalAccessToken =  PersonalAccessToken::where('id', $personalAccessToken->id)->first();
        $data = [
            'access_token' =>  $personalAccessToken->token
        ];
        return successResponse($data,200);
       
    }
}
