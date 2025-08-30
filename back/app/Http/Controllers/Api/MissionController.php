<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MissionResource;
use App\Http\Support\ApiResponse;
use App\Models\Mission;
use Auth;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    /** @noinspection PhpUnusedParameterInspection */
    public function toggleComplete(Request $request, $mission_id)
    {
        $team = Auth::guard('team')->user();

        $mission = Mission::findOrFail($mission_id);

        if ($team->missions()->where('missions.id', $mission->id)->exists()) {
            $team->missions()->detach($mission);
            return ApiResponse::success(new MissionResource($mission), 'JOINED', 'ماموریت با موفقیت از حالت تکمیل خارج شد');
        } else {
            $team->missions()->attach($mission);
            return ApiResponse::success(new MissionResource($mission), 'JOINED', 'ماموریت با موفقیت تکمیل شد');
        }
    }

    public function show($mission)
    {
        $mission = Mission::findOrFail($mission);
        $mission->load(['tasks']);
        return ApiResponse::success([
            ...$mission,

        ]);
    }
}
