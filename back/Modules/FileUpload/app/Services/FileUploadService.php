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
     * @throws TaskAlreadyDoneException
     * @throws Throwable
     */
    public function answer(Team $team, FileUpload $fileUpload, array $data): FileUploadTeam
    {
        $file = $data['file'];

        $task = Task::where('taskable_type', FileUpload::class)
            ->where('taskable_id', $fileUpload->id)
            ->where('type', TaskType::UploadFile->value)
            ->firstOrFail();

        if (TaskTeam::where('task_id', $task->id)->where('team_id', $team->id)->exists()) {
            throw new TaskAlreadyDoneException();
        }

        $fileUploadTeam = null;

        DB::transaction(function () use ($team, $fileUpload, $file, $task, $data, &$fileUploadTeam) {
            TaskTeam::create([
                'task_id' => $task->id,
                'team_id' => $team->id,
            ]);

            $fileUploadTeam = FileUploadTeam::create([
                'team_id' => $team->id,
                'file_upload_id' => $fileUpload->id,
            ]);

            ScoreTeam::create([
                'team_id' => $team->id,
                'score' => $task->score,
                'scorable_id' => $task->id,
                'scorable_type' => Task::class,
            ]);

            /** @noinspection PhpUndefinedMethodInspection */
            $fileUploadTeam->addMedia($file);
        });

        return $fileUploadTeam;
    }
}
