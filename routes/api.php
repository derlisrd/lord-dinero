<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/auth/check',[AuthController::class,'check'])->name('auth.check');
    Route::post('/auth/logout',[AuthController::class,'logout'])->name('auth.logout');

});



Route::post('/auth/login',[AuthController::class,'login'])->name('auth.login');
