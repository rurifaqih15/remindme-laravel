<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ReminderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('session',[SessionController::class, 'createSession']);
Route::put('session',[SessionController::class,'updateToken']);

Route::group([
    'prefix' => 'reminders',
    'middleware' => 'customAuth'
], function () {
       Route::get('/',[ReminderController::class,'index']);
       Route::post('/',[ReminderController::class,'store']);
       Route::get('/{id}',[ReminderController::class,'show']);
       Route::put('/{id}',[ReminderController::class,'update']);
       Route::delete('/{id}',[ReminderController::class,'destroy']);
});
