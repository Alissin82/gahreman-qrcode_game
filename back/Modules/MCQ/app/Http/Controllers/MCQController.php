<?php

namespace Modules\MCQ\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MCQ\Http\Requests\AnswerMCQRequest;
use Modules\MCQ\Models\MCQ;
use Modules\MCQ\Services\MCQService;
use Modules\Support\Responses\ApiResponse;
use Modules\Task\Models\Task;

class MCQController extends Controller
{
    public function __construct(
        private readonly MCQService $mcqService
    )
    {
    }

    public function answer(AnswerMCQRequest $request, Task $task, MCQ $mcq)
    {
        $team = $request->user('team');
        $data = $request->validated();

        $mcqTeam = $this->mcqService->answer($team, $task, $mcq, $data);

        return ApiResponse::success($mcqTeam);
    }
}
