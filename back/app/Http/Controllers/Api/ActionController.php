<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Http\Support\ApiResponse;
use App\Models\Action;
use App\Models\ActionTeam;
use App\Models\Region;
use Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

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
                    'completed' => 0, // TODO
                ],
            ],
        ]);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function start(Request $request, $action_id)
    {
        $team = Auth::guard('team')->user();

        $action = Action::findOrFail($action_id);

        $team->actions()->attach($action, [
            'status' => ActionStatus::Pending->value
        ]);

        return ApiResponse::success(new ActionResource($action), 'JOINED', 'عملیات با موفقیت شروع شد');
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function end(Request $request, $action_id)
    {
        $team = Auth::guard('team')->user();

        $action = Action::findOrFail($action_id);

        $team->actions()->updateExistingPivot($action->id, [
            'status' => ActionStatus::Completed->value
        ]);

        return ApiResponse::success(new ActionResource($action), 'JOINED', 'عملیات با موفقیت تکمیل شد');
    }

    public function show($action_id)
    {
        $team = Auth::guard('team')->user();
        $action = Action::findOrFail($action_id);
        $action->load(['region', 'missions', 'missions.tasks']);
        $teamCompletedMissions = $team?->missions()?->whereHas('action', function ($query) use ($action) {
            $query->where('action_id', $action->id);
        })->count();
        return ApiResponse::success(array_merge([
            'completed_mission_count' => $teamCompletedMissions,
        ], $action->toArray()));

    }
}
