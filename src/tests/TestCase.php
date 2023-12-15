<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Str;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createAccessToken(User $user){
        return $user->tokens()->create([
            'name' => 'access-token',
            'token' => Str::uuid(),
            'refresh_token' => Str::uuid()
        ])->token;
    }

    public function runSeed(){
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
    }
}
