<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoinResource;
use App\Http\Resources\MissionResource;
use App\Http\Support\ApiResponse;
use App\Models\Mission;
use Auth;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index()
    {
        $data = Mission::with(['tasks','action'])->get();
        return ApiResponse::success(CoinResource::collection($data));
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function complete(Request $request, Mission $mission)
    {
        $team = Auth::guard('team')->user();

        $team->missions()->attach($mission);

        return ApiResponse::success(new CoinResource($mission), 'JOINED', 'ماموریت با موفقیت تکمیل شد');
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
