<?php

namespace App\Http\Controllers\Api;

use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Melipayamak\MelipayamakApi;

class AuthController extends Controller
{
    public function otp(Request $request)
    {
        $data = Validator::make($request->all(), [
            'phone' => 'required|string|max:255',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $username = env('MELIPAYAMAK_USERNAME');
        $password = env('MELIPAYAMAK_PASSWORD');


        $now = now()->timestamp;
        $newOtpExpire = $now + 120;

        if ($user->otp && $user->otp > $now) {
            return response()->json(['error' => 'Please wait before requesting a new OTP'], 429);
        }
        $user->update(['otp' => $newOtpExpire]);

        // Generate token based on phone + timestamp
        $code = rand(100000, 999999);
        $token = hash('sha256', $code . $user->phone . $newOtpExpire);

        $api = new MelipayamakApi($username, $password);

        return response()->json([
            'message' => 'OTP sent successfully',
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users',
            'otp'   => 'required|string|max:255',
            'token' => 'required|string|max:255',
            'age'   => 'required|integer|min:0|max:120',
            'gender' => 'required|boolean',
        ]);
        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }

        $time = User::where('phone', $data['phone'])->value('otp');
        $hash = hash('sha256', $data['otp'] . $data['phone'] . $time);

        if ($hash !== $data['token']) {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }

        $user = User::create([
            'name'   => $data['name'],
            'phone'  => $data['phone'],
            'age'    => $data['age'],
            'gender' => $data['gender'],
            'otp'    => null, // clear OTP after register
        ]);

        $jwt = JWTAuth::fromUser($user);

        return response()->json(['message' => 'User registered and logged in successfully'])
            ->cookie('token', $jwt, 60 * 24, '/', null, true, true, false, 'Strict');
    }

    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp'   => 'required|string',
            'token' => 'required|string',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $hash = hash('sha256', $data['otp'] . $data['phone'] . $user->otp);

        if ($hash !== $data['token']) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $jwt = JWTAuth::fromUser($user);

        return response()->json(['message' => 'Login successful'])
            ->cookie('token', $jwt, 60 * 24, '/', null, true, true, false, 'Strict');
    }

    public function me(Request $request)
    {
        try {
            $token = $request->cookie('token');
            $user = JWTAuth::setToken($token)->authenticate();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->cookie('token');
            JWTAuth::setToken($token)->invalidate();
            return response()->json(['message' => 'Logged out'])
                ->cookie('token', '', -1);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }
}
