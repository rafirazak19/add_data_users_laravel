<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users/list', [App\Http\Controllers\UsersController::class, 'index']);
Route::post('/users', [App\Http\Controllers\UsersController::class, 'store']);
Route::get('/users/{id}', [App\Http\Controllers\UsersController::class, 'show']);
Route::put('/users/{id}', [App\Http\Controllers\UsersController::class, 'update']);
Route::delete('/users/{id}', [App\Http\Controllers\UsersController::class, 'destroy']);
