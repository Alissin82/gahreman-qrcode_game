<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('media/{media}', [MediaController::class, 'download'])->name('media.download');
});
