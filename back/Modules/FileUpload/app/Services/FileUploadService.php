<?php

namespace Modules\FileUpload\Services;

use App\Models\ScoreTeam;
use App\Models\Team;
use DB;
use Modules\FileUpload\Models\FileUpload;
use Modules\FileUpload\Models\FileUploadTeam;
use Modules\Task\Enum\TaskType;
use Modules\Task\Exceptions\TaskAlreadyDoneException;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskTeam;
use Throwable;

class FileUploadService
{
    /**
     * @throws TaskAlreadyDoneException|Throwable
     */
    public function answer(Team $team, FileUpload $fileUpload, array $data): FileUploadTeam {
        $file = $data['file'];

        \Log::info('--- Service Entry ---', [
            'team_id' => $team->id,
            'fileUpload_id' => $fileUpload->id,
            'file' => is_object($file) ? get_class($file) : $file,
        ]);

        $task = Task::where('taskable_type', FileUpload::class)
            ->where('taskable_id', $fileUpload->id)
            ->where('type', TaskType::UploadFile->value)
            ->firstOrFail();

        \Log::info('Task Loaded', [
            'task_id' => $task->id,
            'task_score' => $task->score,
            'task_type' => $task->type,
            'task_taskable_id' => $task->taskable_id,
        ]);

        if (TaskTeam::where('task_id', $task->id)->where('team_id', $team->id)->exists()) {
            \Log::warning('Task Already Done', [
                'task_id' => $task->id,
                'team_id' => $team->id,
            ]);
            throw new TaskAlreadyDoneException();
        }

        DB::beginTransaction();

        $taskTeam = TaskTeam::create([
            'task_id' => $task->id,
            'team_id' => $team->id,
        ]);
        \Log::info('TaskTeam Created', $taskTeam->toArray());

        $fileUploadTeam = FileUploadTeam::create([
            'team_id' => $team->id,
            'file_upload_id' => $fileUpload->id,
        ]);
        \Log::info('FileUploadTeam Created', $fileUploadTeam->toArray());

        $scoreTeam = ScoreTeam::create([
            'team_id' => $team->id,
            'score' => $task->score,
            'scorable_id' => $task->id,
            'scorable_type' => Task::class,
        ]);
        \Log::info('ScoreTeam Created', $scoreTeam->toArray());

        /** @noinspection PhpUndefinedMethodInspection */
        $fileUploadTeam->addMedia($file);
        \Log::info('File Attached To FileUploadTeam');

        DB::commit();

        \Log::info('Transaction Committed Successfully');

        return $fileUploadTeam;
    }
}
