<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/users/register", [AuthController::class, "register"])->name("users.register");
Route::post("/users/login", [AuthController::class, "login"])->name("users.login");
Route::post("/users/logout", [AuthController::class, "logout"])->name("users.logout")->middleware("auth:sanctum");
Route::post("/forgot-password", [AuthController::class, "forgotPassword"])->name("password.forgot")->middleware("guest");
Route::post("/reset-password",[AuthController::class,"resetPassword"])->name("password.reset")->middleware("guest");