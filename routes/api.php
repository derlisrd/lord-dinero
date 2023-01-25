<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\MetodosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/auth/check',[AuthController::class,'check'])->name('auth.check');
    Route::post('/auth/logout',[AuthController::class,'logout'])->name('auth.logout');
    Route::get('/clientes',[ClientesController::class,'index']);
    Route::get('/cliente/{id}',[ClientesController::class,'show']);
});

Route::get('/auth/check',[MetodosController::class,'not_found']);
Route::get('/auth/login',[MetodosController::class,'not_found']);

Route::post('/auth/login',[AuthController::class,'login'])->name('auth.login');
