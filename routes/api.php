<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/users/register", [AuthController::class, "register"])->name("users.register");
Route::post("/users/login", [AuthController::class, "login"])->name("users.login");
Route::post("/users/logout", [AuthController::class, "logout"])->name("users.logout")->middleware("auth:sanctum");