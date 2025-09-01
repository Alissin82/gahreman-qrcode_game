<?php

namespace Modules\FileUpload\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\FileUpload\Http\Requests\AnswerFileUploadRequest;
use Modules\FileUpload\Models\FileUpload;
use Modules\FileUpload\Services\FileUploadService;
use Modules\Support\Responses\ApiResponse;
use Modules\Task\Exceptions\TaskAlreadyDoneException;

class FileUploadController extends Controller
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    )
    {
    }

    public function answer(AnswerFileUploadRequest $request, FileUpload $fileUpload)
    {
        $team = $request->user('team');
        $data = $request->validated();

        try {
            $fileUploadTeam = $this->fileUploadService->answer($team, $fileUpload, $data);
            return ApiResponse::success($fileUploadTeam);
        } catch (TaskAlreadyDoneException $e) {
            return ApiResponse::fail('Task already done');

        }

    }
}
