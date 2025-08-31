<?php

use Illuminate\Support\Facades\Route;
use Modules\FileUpload\Http\Controllers\FileUploadController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::controller(FileUploadController::class)->group(function () {
        Route::post('tasks/{task}/file-upload/{fileUpload}', 'answer');
    });
});
