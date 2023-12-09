<?php

use App\Models\PersonalAccessToken;
use Carbon\Carbon;

if (!function_exists('successResponse')){
    function successResponse($data=[],$statusCode = 200 ){
        return response()->json([
            'ok' => true,
            'data' => $data
        ],$statusCode);
    }
}

if (!function_exists('errorResponse')){
    function errorResponse($err = '',$msg = 'Error', $statusCode = 400){
            return response()->json([
                'ok' => false,
                'err' => $err,
                'msg' => $msg
            ],$statusCode);
        }
}


if (!function_exists('isValidAuthorizationHeader')){
    function isValidAuthorizationHeader($header){
        return preg_match('/^Bearer\s+(.*?)$/', $header);
    }
}

if (!function_exists('isValidAccessToken')){
    function isValidAccessToken($token){
        $accessToken = PersonalAccessToken::where('name','access-token')
        ->where('token',$token)->first();
        if($accessToken){
            $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $accessToken->expires_at);
            $isExpired = Carbon::now()->greaterThan($expiresAt);
        }
        if($accessToken && !$isExpired){
            return true;
        }
        return false;
    }
}