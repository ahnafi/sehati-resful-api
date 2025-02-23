<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function profile(Request $request): UserResource
    {
        $user = $request->user();

        $profile = Cache::remember("user_profile_{$user->id}", now()->addMinute(30), function () use ($user) {
            return User::where("id", $user->id)->first();
        });

        return new UserResource($profile);
    }
}
