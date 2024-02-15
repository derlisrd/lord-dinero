<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MovementsController;

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/auth/check',[AuthController::class,'check']);
    Route::post('/auth/logout',[AuthController::class,'logout']);


    Route::prefix('category')->group(function () {
        Route::get('/',[CategoriesController::class,'index']);
        Route::post('/',[CategoriesController::class,'store']);
        Route::put('/{id}',[CategoriesController::class,'update']);
        Route::delete('/{id}',[CategoriesController::class,'update']);
        Route::get('/{id}/movements',[CategoriesController::class,'movementsByCategory']);
    });
    Route::prefix('movements')->group(function () {
        Route::get('/{id}',[MovementsController::class,'show']);
        Route::get('/',[MovementsController::class,'index']);
        Route::post('/',[MovementsController::class,'store']);
        Route::put('/{id}',[MovementsController::class,'update']);
        Route::delete('/{id}',[MovementsController::class,'update']);
    });

});


Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/forgot',[AuthController::class,'forgot']);
Route::post('/auth/code',[AuthController::class,'code']);
Route::post('/auth/reset',[AuthController::class,'reset']);


Route::fallback(function () {
    return response()->json(["success"=>false,'message'=>'Not found route'],404);
});
