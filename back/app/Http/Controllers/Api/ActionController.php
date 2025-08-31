<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Models\Action;
use App\Models\ActionTeam;
use App\Models\Mission;
use App\Models\Region;
use App\Models\ScoreTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Modules\Support\Responses\ApiResponse;

class ActionController extends Controller
{
    public function index()
    {
        $team = Auth::guard('team')->user();

        $data = Action::with([
            'missions',
            'region',
            'actionTeams' => function (HasMany $builder) use ($team) {
                if ($team)
                    $builder->whereTeamId($team->id);
            }
        ])->get();

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
                    'completed' => Region::with('actions')
                        ->withCount('actions')
                        ->having('actions_count', '>', 0)
                        ->get()
                        ->filter(function (Region $region) {
                            return $region->actions->every(function (Action $action) {
                                $meta = $action->meta;
                                return $meta['total'] === $meta['completed'];
                            });
                        })->values()
                        ->map(function (Region $region) {
                            /** @noinspection PhpUndefinedFieldInspection */
                            $region->completed = true;
                            return $region;
                        })->count(),
                ],
            ],
        ]);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function start(Request $request, $action_id)
    {
        $team = Auth::guard('team')->user();

        $action = Action::findOrFail($action_id);

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
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
    public function end(Request $request, $action_id)
    {
        $team = Auth::guard('team')->user();

        $action = Action::findOrFail($action_id);

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
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

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $team->actions()->updateExistingPivot($action->id, [
            'status' => ActionStatus::Completed
        ]);

        $team->missions()->sync(Mission::where('action_id', $action_id)->pluck('id'));
        return ApiResponse::success(new ActionResource($action), 'JOINED', 'عملیات با موفقیت تکمیل شد');
    }

    public function show($action_id)
    {
        $team = Auth::guard('team')->user();
        $action = Action::with(['region', 'missions', 'missions.tasks', 'icon', 'attachment'])->findOrFail($action_id);
        $teamCompletedMissions = $team?->missions()?->whereHas('action', function ($query) use ($action) {
            $query->where('action_id', $action->id);
        })->count();

        return ApiResponse::success([
            'completed_mission_count' => $teamCompletedMissions,
            ...(new ActionResource($action))->toArray(request())
        ]);
    }
}
