<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotifyResource;
use Modules\Support\Responses\ApiResponse;

class NotifyController extends Controller
{
    public function teamNotifications()
    {
        $team = \Auth::guard('team')->user();

        $notifs = $team->notifies;

        return ApiResponse::success(NotifyResource::collection($notifs));
    }
}
