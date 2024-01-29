<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/auth/check',[AuthController::class,'check'])->name('auth.check');
    Route::post('/auth/logout',[AuthController::class,'logout'])->name('auth.logout');

});

Route::get('/',function(){
    return response()->json([
        "success"=>false
    ],401);
});


Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/forgot',[AuthController::class,'forgot']);
Route::post('/auth/reset',[AuthController::class,'reset']);
