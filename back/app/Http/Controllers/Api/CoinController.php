<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoinResource;
use App\Models\Coin;
use App\Models\ScoreTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\Support\Responses\ApiResponse;

class CoinController extends Controller
{
    public function index()
    {
        $data = Coin::get();
        return ApiResponse::success(CoinResource::collection($data));
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function exchange(Request $request, Coin $coin)
    {
        $team = Auth::guard('team')->user();

        if ($team->coins()->where('id', $coin->id)->exists()) {
            return ApiResponse::fail('کارت سکه قبلا برای تیم شما استفاده شده است.');
        }

        ScoreTeam::create([
            'team_id' => $team->id,
            'score' => $coin->coin,
            'scorable_id' => $coin->id,
            'scorable_type' => Coin::class,
        ]);
        $team->coins()->attach($coin);
        return ApiResponse::success(new CoinResource($coin), 'JOINED', 'سکه ها با موفقیت اضافه شدند شد');
    }
}
