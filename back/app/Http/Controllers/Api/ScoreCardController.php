<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScoreCardResource;
use App\Http\Support\ApiResponse;
use App\Models\ScoreCard;
use Auth;
use Illuminate\Http\Request;

class ScoreCardController extends Controller
{
    public function index()
    {
        $data = ScoreCard::get();
        return ApiResponse::success(ScoreCardResource::collection($data));
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function exchange(Request $request, ScoreCard $scoreCard)
    {
        $team = Auth::guard('team')->user();

        if ($team->scoreCards()->where('id', $scoreCard->id)->exists()) {
            return ApiResponse::fail('کارت امتیاز قبلا برای تیم شما استفاده شده است.');
        } else {
            $team->scoreCards()->attach($scoreCard);
            return ApiResponse::success(new ScoreCardResource($scoreCard), 'JOINED', 'امتیاز ها با موفقیت اضافه شدند شد');
        }
    }
}
