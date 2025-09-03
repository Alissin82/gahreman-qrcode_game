<?php

namespace Modules\FileUpload\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\FileUpload\Http\Requests\AnswerFileUploadRequest;
use Modules\FileUpload\Models\FileUpload;
use Modules\FileUpload\Services\FileUploadService;
use Modules\Support\Responses\ApiResponse;
use Modules\Task\Exceptions\TaskAlreadyDoneException;
use Throwable;

class FileUploadController extends Controller
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    )
    {
    }

    public function answer(AnswerFileUploadRequest $request, $fileUploadId)
    {
        $team = $request->user('team');
        $data = $request->validated();

        \Log::info('--- Controller ---', [
            'fileUploadId' => $fileUploadId,
            'team_id' => $team->id ?? null,
            'validated_data' => $data,
        ]);

        $fileUpload = FileUpload::findOrFail($fileUploadId);

        \Log::info('Loaded FileUpload', [
            'fileUpload' => $fileUpload->toArray(),
        ]);

        try {
            try {
                $fileUploadTeam = $this->fileUploadService->answer($team, $fileUpload, $data);

                \Log::info('Service Response', [
                    'fileUploadTeam' => $fileUploadTeam->toArray(),
                ]);

                return ApiResponse::success($fileUploadTeam);
            } catch (TaskAlreadyDoneException|Throwable $e) {
                \Log::error('Inner Exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return ApiResponse::fail('خطا ناشناخته ای رخ داد.');
            }
        } catch (TaskAlreadyDoneException $e) {
            \Log::warning('TaskAlreadyDoneException triggered');
            return ApiResponse::fail('قبلا این وظیفه را انجام داده اید.');
        }
    }

}
