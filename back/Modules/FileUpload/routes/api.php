<?php

use Illuminate\Support\Facades\Route;
use Modules\FileUpload\Http\Controllers\FileUploadController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('fileuploads', FileUploadController::class)->names('fileupload');
});
