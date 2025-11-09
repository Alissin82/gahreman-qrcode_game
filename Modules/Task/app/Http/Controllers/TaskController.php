<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Support\Responses\ApiResponse;
use Modules\Task\Models\Task;
use Modules\Task\Resources\TaskResource;

class TaskController extends Controller
{
    public function show(Task $task): JsonResponse
    {
        $task->load('taskable');
        $task->action->loadCount('tasks');

        return ApiResponse::success(new TaskResource($task));
    }
}
