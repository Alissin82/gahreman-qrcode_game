<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Http\Support\ApiResponse;
use App\Models\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function index()
    {
        $data = Action::with(['missions', 'region'])->get();
        return ApiResponse::success(ActionResource::collection($data));
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function start(Request $request, Action $action)
    {
        $team = \Auth::guard('team')->user();

        $team->actions()->attach($action);

        return ApiResponse::success(new ActionResource($action), 'JOINED', 'عملیات با موفقیت شروع شد');
    }
}
