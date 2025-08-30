<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Support\ApiResponse;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $team = Team::where('hash', $request->hash)->first();

        if (!$team) {
            return response()->json(['message' => 'Invalid hash.'], 401);
        }

        $team->tokens()->delete();
        $token = $team->createToken('api-token')->plainTextToken;

        return response()->json([
            'team' => $team,
            'token' => $token,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success($request->user('team'));
    }

    public function logout(Request $request): JsonResponse
    {
        $team = $request->user('team');

        $team->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
