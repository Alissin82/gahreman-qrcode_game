<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('teams')->name('teams.')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(App\Http\Controllers\Api\ActionTeamController::class)->group(function () {
        Route::post('/actions/{action}/upload', 'upload');
    });


    // leaderboard
    Route::controller(App\Http\Controllers\Api\LeaderboardController::class)->prefix('leaderboard')->name('leaderboard.')->group(function () {
        Route::get('/', 'index');
    });

    Route::controller(App\Http\Controllers\Api\ActionController::class)->group(function () {
        Route::get('/actions', 'index');
        Route::post('/actions/{action}/start', 'start');
        Route::post('/actions/{action}/end', 'end');
        Route::get('/actions/{action}', 'show');
    });

    Route::controller(App\Http\Controllers\Api\CoinController::class)->prefix('coins')->name('coins.')->group(function () {
        Route::get('/', 'index');
        Route::post('/{coin}', 'exchange');
    });
    Route::controller(App\Http\Controllers\Api\ScoreCardController::class)->prefix('score-cards')->name('score-cards.')->group(function () {
        Route::get('/', 'index');
        Route::post('/{scoreCard}', 'exchange');
    });
    Route::controller(App\Http\Controllers\Api\GameController::class)->prefix('games')->name('games.')->group(function () {
        Route::get('/', 'index');
        Route::get('/{game}', 'show');
        Route::post('/{game}', 'exchange');
        Route::get('/score', 'score');
    });
});
