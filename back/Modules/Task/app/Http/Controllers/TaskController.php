<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCheckAnswerRequest;
use Illuminate\Http\Request;
use Modules\Task\Task\Task;
use Response\ApiResponse;

class TaskController extends Controller
{
    public function show(Request $request, Task $task)
    {
        return ApiResponse::success($task);
    }
    public function checkAnswer(TaskCheckAnswerRequest $request, Task $task)
    {
        if ($task->answer == $request->get('answer')) {
            return ApiResponse::success();
        } else {
            return ApiResponse::fail("Wrong Answer");
        }
    }
}
