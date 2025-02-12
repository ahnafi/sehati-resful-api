<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/users/register", [AuthController::class, "register"])->name("users.register");
