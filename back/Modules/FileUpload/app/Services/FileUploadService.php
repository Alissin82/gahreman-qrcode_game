<?php

namespace Modules\FileUpload\Services;

use App\Models\ScoreTeam;
use App\Models\Team;
use DB;
use Modules\FileUpload\Models\FileUpload;
use Modules\FileUpload\Models\FileUploadTeam;
use Modules\Task\Exceptions\TaskAlreadyDoneException;
use Modules\Task\Models\Task;
use Throwable;

class FileUploadService
{
    /**
     * @throws TaskAlreadyDoneException|Throwable
     */
    public function answer(Team $team, FileUpload $fileUpload, array $data): FileUploadTeam {
        $file = $data['file'];
        
        $task = $fileUpload->task;

        if ($team->tasks()->where('tasks.id', $task->id)->exists()) {
            throw new TaskAlreadyDoneException();
        }

        DB::beginTransaction();
        $team->tasks()->attach($task->id);

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

        DB::commit();

        return $fileUploadTeam;
    }
}
