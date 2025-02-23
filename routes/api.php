<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/users/register", [AuthController::class, "register"])->name("users.register");
Route::post("/users/login", [AuthController::class, "login"])->name("users.login");
Route::post("/users/logout", [AuthController::class, "logout"])->name("users.logout")->middleware("auth:sanctum");
Route::post("/forgot-password", [AuthController::class, "forgotPassword"])->name("password.forgot")->middleware("guest");
Route::post("/reset-password", [AuthController::class, "resetPassword"])->name("password.reset")->middleware("guest");
Route::get("/email/verify/{id}/{hash}", [AuthController::class, "verifyEmail"])->name("verification.verify")->middleware(['auth:sanctum', 'signed', 'throttle:6,1']);
Route::post('/email/verification-notification', [AuthController::class, "VerifyNotification"])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
Route::get("/users/current", [\App\Http\Controllers\UserController::class, "profile"])->middleware("auth:sanctum");
