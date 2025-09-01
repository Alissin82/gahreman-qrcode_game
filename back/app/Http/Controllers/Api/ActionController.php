<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Models\Action;
use App\Models\ActionTeam;
use App\Models\Region;
use App\Models\ScoreTeam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\Support\Responses\ApiResponse;
use Modules\Task\Models\Task;

class ActionController extends Controller
{
    public function index()
    {
        $team = Auth::guard('team')->user();

        $data = Action::with(['region', 'actionTeams.team', 'actionTeams.team.tasks', 'icon'])->get();


        $data->loadCount('tasks');


        return ApiResponse::success([
            'actions' => ActionResource::collection($data),
            'meta' => [
                'actions' => [
                    'total' => Action::count(),
                    'completed' => ActionTeam::whereTeamId($team->id)
                        ->where('status', ActionStatus::Completed->value)
                        ->count(),
                ],
                'regions' => [
                    'total' => Region::count(),
                    'completed' => Region::whereHas('actions', function (Builder $actionQuery) use ($team) {
                        $actionQuery->whereHas('actionTeams', function (Builder $teamQuery) use ($team) {
                            $teamQuery->where('team_id', $team->id)
                                ->whereStatus(ActionStatus::Completed);
                        });
                    })->count(),
                ],
            ],
        ]);
    }

    public function start(Request $request, $action_id)
    {
        $team = Auth::guard('team')->user();

        $action = Action::findOrFail($action_id);

        if ($action->region->locked)
            return ApiResponse::fail(new ActionResource($action), 'LOCKED', 'عملیات رزرو شده است');

        $actionTeam = ActionTeam::where('team_id', $team->id)->where('action_id', $action->id)->first();

        if (!$actionTeam) {
            $team->actions()->attach($action, [
                'status' => ActionStatus::Pending
            ]);

            if ($action->region->lockable) {
                $action->region->locked = true;
                $action->region->save();
            }

            return ApiResponse::success(new ActionResource($action), 'JOINED', 'عملیات با موفقیت شروع شد');
        }

        if ($actionTeam->status == ActionStatus::Pending) {
            return ApiResponse::fail('عملیات قبلا برای تیم شما شروع شده است.');
        }

        return ApiResponse::fail('عملیات به پایان رسیده را نمی‌توان شروع کرد.');
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function end(Request $request, Action $action)
    {
        $team = Auth::guard('team')->user();

        $actionTeam = ActionTeam::where('team_id', $team->id)->where('action_id', $action->id)->first();

        if (!$actionTeam) {
            return ApiResponse::fail('عملیات هنوز شروع نشده است.');
        }


        if ($actionTeam->status == ActionStatus::Completed) {
            return ApiResponse::fail('عملیات قبلا به پایان رسیده است.');
        }

        ScoreTeam::create([
            'team_id' => $team->id,
            'score' => $action->score,
            'scorable_id' => $action->id,
            'scorable_type' => Action::class,
        ]);

        if ($action->region->locked) {
            $action->region->locked = false;
            $action->region->save();
        }

        $team->actions()->updateExistingPivot($action->id, [
            'status' => ActionStatus::Completed
        ]);

        return ApiResponse::success(new ActionResource($action), 'JOINED', 'عملیات با موفقیت تکمیل شد');
    }

    public function show($action_id)
    {
        $team = Auth::guard('team')->user();
        $teamLatestTask = $team->tasks()->whereActionId($action_id)->orderBy('order')->first();
        $action = Action::with(['region', 'tasks', 'icon', 'attachmentBoy', 'attachmentGirl', 'tasks.teams', 'actionTeams'])->findOrFail($action_id);
        $action->tasks->map(function (Task $task) use ($teamLatestTask, $team) {
            $task->done_by_team = $task->teams->where('id', $team->id)->first();
            $task->locked_for_team = $task->order > (($teamLatestTask?->order ?? -1) + 1);
        });
        $teamCompletedTasks = $team->tasks()->where('action_id', $action_id)->count();

        return ApiResponse::success([
            'team_completed_task_count' => $teamCompletedTasks,
            ...(new ActionResource($action))->toArray(request())
        ]);
    }
}
