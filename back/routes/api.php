<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('teams')->name('teams.')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:team')->group(function () {
        Route::post('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:team')->group(function () {
    Route::controller(App\Http\Controllers\Api\ActionController::class)->group(function () {
        Route::get('/actions', 'index');
        Route::post('/actions/{action}', 'start');
    });
    Route::controller(App\Http\Controllers\Api\CoinController::class)->prefix('coins')->name('coins.')->group(function () {
        Route::get('/', 'index');
        Route::post('/{coin}', 'exchange');
    });
    Route::controller(App\Http\Controllers\Api\ScoreCardController::class)->prefix('score-cards')->name('score-cards.')->group(function () {
        Route::get('/', 'index');
        Route::post('/{scoreCard}', 'exchange');
    });
    Route::controller(App\Http\Controllers\Api\MissionController::class)->prefix('missions')->name('missions.')->group(function () {
        Route::get('/', 'index');
        Route::post('/{mission}', 'complete');
    });
});
