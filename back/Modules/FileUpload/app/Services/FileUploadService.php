<?php

namespace Modules\FileUpload\Services;

use App\Models\ScoreTeam;
use App\Models\Team;
use Modules\FileUpload\Models\FileUpload;
use Modules\FileUpload\Models\FileUploadTeam;
use Modules\Task\Models\Task;

class FileUploadService
{
    public function answer(Team $team, Task $task, FileUpload $fileUpload, array $data): FileUpload {
        $file = $data['file'];

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

        $fileUploadTeam->addMedia($file);
        return $fileUploadTeam;
    }
}
