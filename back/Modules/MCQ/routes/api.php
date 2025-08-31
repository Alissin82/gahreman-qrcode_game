<?php

use Illuminate\Support\Facades\Route;
use Modules\MCQ\Http\Controllers\MCQController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::controller(MCQController::class)->name('mcq.')->group(function () {
        Route::post('tasks/{task}/mcq/{mcq}', 'answer');
    });

});
