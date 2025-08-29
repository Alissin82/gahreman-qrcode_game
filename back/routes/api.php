<?php

use App\Http\Controllers\ActionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TeamController;

Route::prefix('teams')->name('teams.')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:team-api')->group(function () {
        Route::post('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/actions', [ActionController::class, 'index']);
Route::get('/actions/{id}', [ActionController::class, 'show']);

Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);
Route::post('/teams', [TeamController::class, 'store']);
Route::put('/teams/{id}', [TeamController::class, 'update']);
Route::delete('/teams/{id}', [TeamController::class, 'destroy']);
