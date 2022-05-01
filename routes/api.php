<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware'=>'auth:sanctum'],function(){

    Route::get('/user',[AuthController::class,'info']);
    Route::get('/logout',[AuthController::class,'logout']);
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
//payment section
Route::get('/pay',[AuthController::class,'payorder']);
Route::get('/callback',[AuthController::class,'success']);
Route::get('/error',[AuthController::class,'error']);
