<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "user" => UserResource::make($user),
            "token_type" => "Bearer",
            "token" => $token,
        ], 201);
    }
}
