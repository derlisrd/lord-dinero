<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/auth/check',[AuthController::class,'check']);
    Route::post('/auth/logout',[AuthController::class,'logout']);

});


Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/forgot',[AuthController::class,'forgot']);
Route::post('/auth/code',[AuthController::class,'code']);
Route::post('/auth/reset',[AuthController::class,'reset']);


Route::fallback(function () {
    return response()->json(["success"=>false,'message'=>'Not found fall'],404);
});
