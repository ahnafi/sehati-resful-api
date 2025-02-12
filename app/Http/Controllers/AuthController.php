<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Request;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "data" => [
                "user" => UserResource::make($user),
                "token_type" => "Bearer",
                "token" => $token,
            ]
        ], 201);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where("email", $data["email"])->first();

        if (!$user || !Hash::check($data["password"], $user->password)) {
            return response()->json([
                "errors" => [
                    "message" => ["Unauthorized"]
                ]
            ], 401);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "data" => [
                "user" => UserResource::make($user),
                "token_type" => "Bearer",
                "token" => $token,
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // $request->user()->tokens()->delete(); delete token from all device 
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "data" => true
        ]);
    }
}
