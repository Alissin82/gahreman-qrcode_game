<?php

namespace Modules\Task\Observers;


use Modules\FileUpload\Models\FileUpload;
use Modules\MCQ\Models\MCQ;
use Modules\Task\Enum\TaskType;
use Modules\Task\Models\Task;

class TaskObserver
{
    public function saving(Task $task): void
    {
        $type = match($task->taskable_type) {
            MCQ::class =>  TaskType::MCQ,
            FileUpload::class => TaskType::UploadFile
        };
        $task->type = $type;
    }
}
