<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCheckAnswerRequest;
use App\Http\Support\ApiResponse;
use App\Models\Task;
use Illuminate\Http\Request;

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
