<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\ScoreTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Support\Responses\ApiResponse;

class LeaderboardController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('scoreTeams')->orderBy('score_teams_count', 'desc')->limit(10)->get();

        return ApiResponse::success([
            'teams' => TeamResource::collection($teams),
        ]);
    }
}
