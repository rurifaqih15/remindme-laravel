<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Auth;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');
        if (!isValidAuthorizationHeader($authorizationHeader)) {
            throw new HttpException(401,'Invalid access token');
        }
        $accessToken = str_replace('Bearer ', '', $authorizationHeader);
        if (!isValidAccessToken($accessToken)) {
            throw new HttpException(401,'Invalid access token');
        }
        Auth::loginUsingId(PersonalAccessToken::where('token',$accessToken)->first()->tokenable_id);
        return $next($request);
    }
}
