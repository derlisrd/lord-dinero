<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/auth/check',[AuthController::class,'check']);
    Route::post('/auth/logout',[AuthController::class,'logout']);
});

Route::get('/',function(){return response()->json(["success"=>false],401);});

Route::group(['prefix'=>'auth'], function(){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/forgot',[AuthController::class,'forgot']);
    Route::post('/reset',[AuthController::class,'reset']);
});


