<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth/login',[AuthController::class,'redirectToIdentity']);
Route::get('/oauth/callback',[AuthController::class,'callback']);

Route::post('/logout',[AuthController::class,'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/me',[UserController::class,'me']);
});